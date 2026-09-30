<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\ProjectDetail;
use App\Models\ProjectDocument;
use App\Models\ProjectMedia;
use App\Models\UnitConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectWizardController extends Controller
{
    /**
     * Step 1: Save or update basic project details.
     */
    public function saveBasicDetails(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id'     => ['nullable', 'integer', 'exists:project_details,id'],
            'name'           => ['required', 'string', 'max:255'],
            'tagline'        => ['nullable', 'string', 'max:255'],
            'builder_name'   => ['required', 'string', 'max:255'],
            'location'       => ['required', 'string', 'max:255'],
            'maps_link'      => ['nullable', 'string', 'max:1000'],
            'project_type'   => ['required', 'string', 'max:100'],
            'project_status' => ['required', 'string', 'max:100'],
            'possession_date'=> ['nullable', 'date'],
            'rera_number'    => ['nullable', 'string', 'max:100'],
            'towers'         => ['nullable', 'integer', 'min:0'],
            'total_units'    => ['nullable', 'integer', 'min:0'],
            'land_area'      => ['nullable', 'string', 'max:100'],
            'description'    => ['nullable', 'string'],
            'highlights'     => ['nullable', 'array'],
            'highlights.*'   => ['string', 'max:255'],
        ]);

        $projectId = $validated['project_id'] ?? null;
        unset($validated['project_id']);

        if ($projectId) {
            $project = ProjectDetail::findOrFail($projectId);
            $project->update($validated);
        } else {
            $validated['status'] = 'draft';
            if (auth()->check()) {
                $validated['user_id'] = auth()->id();
            }
            $project = ProjectDetail::create($validated);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Basic details saved successfully.',
            'project_id' => $project->id,
            'project'    => $project,
        ]);
    }

    /**
     * Step 2: Save or update unit configurations and Smart Bargain settings.
     */
    public function saveUnits(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id'             => ['required', 'integer', 'exists:project_details,id'],
            'smart_bargain_enabled'  => ['nullable', 'boolean'],
            'min_expected_price'     => ['nullable', 'numeric', 'min:0'],
            'target_price'           => ['nullable', 'numeric', 'min:0'],
            'max_price'              => ['nullable', 'numeric', 'min:0'],
            'units'                  => ['nullable', 'array'],
            'units.*.id'             => ['nullable', 'integer', 'exists:unit_configurations,id'],
            'units.*.unit_type'      => ['required_with:units', 'string', 'max:100'],
            'units.*.built_up_area'  => ['required_with:units', 'numeric', 'min:1'],
            'units.*.carpet_area'    => ['nullable', 'numeric', 'min:1'],
            'units.*.price'          => ['required_with:units', 'numeric', 'min:0'],
            'units.*.price_per_sqft' => ['nullable', 'numeric', 'min:0'],
            'units.*.available_units'=> ['nullable', 'integer', 'min:0'],
            'units.*.floor_range'    => ['nullable', 'string', 'max:100'],
            'units.*.show_floor_plan'=> ['nullable', 'boolean'],
        ]);

        $project = ProjectDetail::findOrFail($validated['project_id']);

        DB::transaction(function () use ($project, $validated) {
            // Update Smart Bargain settings on the project
            $project->update([
                'smart_bargain_enabled' => $validated['smart_bargain_enabled'] ?? $project->smart_bargain_enabled ?? true,
                'min_expected_price'    => $validated['min_expected_price'] ?? null,
                'target_price'          => $validated['target_price'] ?? null,
                'max_price'             => $validated['max_price'] ?? null,
            ]);

            // Sync unit configurations
            if (!empty($validated['units'])) {
                $keptIds = [];

                foreach ($validated['units'] as $unitData) {
                    $builtUp = (float) ($unitData['built_up_area'] ?? 0);
                    $price = (float) ($unitData['price'] ?? 0);

                    // Compute price per sqft if omitted
                    $perSqft = !empty($unitData['price_per_sqft'])
                        ? (float) $unitData['price_per_sqft']
                        : ($builtUp > 0 ? round($price / $builtUp, 2) : null);

                    $attributes = [
                        'unit_type'       => trim($unitData['unit_type']),
                        'built_up_area'   => $builtUp,
                        'carpet_area'     => !empty($unitData['carpet_area']) ? (float) $unitData['carpet_area'] : $builtUp,
                        'price'           => $price,
                        'price_per_sqft'  => $perSqft,
                        'available_units' => isset($unitData['available_units']) ? (int) $unitData['available_units'] : 1,
                        'floor_range'     => !empty($unitData['floor_range']) ? $unitData['floor_range'] : 'All Floors',
                        'show_floor_plan' => !empty($unitData['show_floor_plan']),
                    ];

                    if (!empty($unitData['id'])) {
                        $unit = UnitConfiguration::where('id', $unitData['id'])
                            ->where('project_detail_id', $project->id)
                            ->first();

                        if ($unit) {
                            $unit->update($attributes);
                            $keptIds[] = $unit->id;
                            continue;
                        }
                    }

                    $newUnit = $project->unitConfigurations()->create($attributes);
                    $keptIds[] = $newUnit->id;
                }

                // Remove unit configurations that were removed from the form
                if (!empty($keptIds)) {
                    $project->unitConfigurations()->whereNotIn('id', $keptIds)->delete();
                }
            }
        });

        return response()->json([
            'success'    => true,
            'message'    => 'Units and pricing saved successfully.',
            'project_id' => $project->id,
            'units'      => $project->unitConfigurations()->get(),
        ]);
    }

    /**
     * Step 3: Save or update project amenities.
     */
    public function saveAmenities(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id'    => ['required', 'integer', 'exists:project_details,id'],
            'amenities'     => ['nullable', 'array'],
            'amenities.*'   => ['string', 'max:100'],
            'other_amenity' => ['nullable', 'string', 'max:100'],
        ]);

        $project = ProjectDetail::findOrFail($validated['project_id']);

        $amenities = $validated['amenities'] ?? [];

        // If builder typed a custom amenity in "Other (Specify)", append it
        if (!empty($validated['other_amenity'])) {
            $custom = trim($validated['other_amenity']);
            if ($custom !== '' && !in_array($custom, $amenities, true)) {
                $amenities[] = $custom;
            }
        }

        // Clean and unique values
        $cleanedAmenities = array_values(array_unique(array_filter(array_map('trim', $amenities))));

        $project->update([
            'amenities' => $cleanedAmenities,
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Amenities saved successfully.',
            'project_id' => $project->id,
            'amenities'  => $project->amenities,
        ]);
    }

    /**
     * Step 4: Save media, videos, floor plans, and preferences into normalized ProjectMedia & ProjectDocument.
     */
    public function saveMedia(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id'        => ['required', 'integer', 'exists:project_details,id'],
            'video_url'         => ['nullable', 'string', 'max:1000'],
            'virtual_tour_url'  => ['nullable', 'string', 'max:1000'],
            'media_photos'      => ['nullable', 'array'],
            'floor_plans'       => ['nullable', 'array'],
            'documents'         => ['nullable', 'array'],
            'media_preferences' => ['nullable', 'array'],
        ]);

        $project = ProjectDetail::findOrFail($validated['project_id']);

        DB::transaction(function () use ($project, $validated) {
            // Save video URL to normalized project_media
            if (!empty($validated['video_url'])) {
                $project->media()->updateOrCreate(
                    ['category' => 'video'],
                    [
                        'title'     => 'Project Video',
                        'file_url'  => trim($validated['video_url']),
                        'file_name' => 'Project Video',
                    ]
                );
            }

            // Save virtual tour URL to normalized project_media
            if (!empty($validated['virtual_tour_url'])) {
                $project->media()->updateOrCreate(
                    ['category' => 'virtual_tour'],
                    [
                        'title'     => 'Virtual Tour',
                        'file_url'  => trim($validated['virtual_tour_url']),
                        'file_name' => 'Virtual Tour',
                    ]
                );
            }

            // Sync photos into project_media if provided
            if (isset($validated['media_photos']) && is_array($validated['media_photos'])) {
                foreach ($validated['media_photos'] as $idx => $photoData) {
                    $url = is_array($photoData) ? ($photoData['url'] ?? '') : $photoData;
                    if (empty($url)) continue;

                    $project->media()->updateOrCreate(
                        [
                            'file_url' => $url,
                        ],
                        [
                            'category'   => is_array($photoData) ? ($photoData['category'] ?? 'photos') : 'photos',
                            'title'      => is_array($photoData) ? ($photoData['name'] ?? 'Project Photo') : 'Project Photo',
                            'file_name'  => is_array($photoData) ? ($photoData['name'] ?? null) : null,
                            'file_path'  => is_array($photoData) ? ($photoData['path'] ?? null) : null,
                            'file_size'  => is_array($photoData) ? ($photoData['size'] ?? null) : null,
                            'is_cover'   => $idx === 0,
                            'sort_order' => $idx,
                        ]
                    );
                }
            }

            // Sync floor plans into project_media if provided
            if (isset($validated['floor_plans']) && is_array($validated['floor_plans'])) {
                foreach ($validated['floor_plans'] as $idx => $planData) {
                    $url = is_array($planData) ? ($planData['url'] ?? '') : $planData;
                    if (empty($url)) continue;

                    $project->media()->updateOrCreate(
                        ['file_url' => $url],
                        [
                            'category'   => 'floor_plans',
                            'title'      => is_array($planData) ? ($planData['name'] ?? 'Floor Plan') : 'Floor Plan',
                            'file_name'  => is_array($planData) ? ($planData['name'] ?? null) : null,
                            'file_path'  => is_array($planData) ? ($planData['path'] ?? null) : null,
                            'file_size'  => is_array($planData) ? ($planData['size'] ?? null) : null,
                            'sort_order' => $idx,
                        ]
                    );
                }
            }

            // Sync documents into project_documents if provided
            if (isset($validated['documents']) && is_array($validated['documents'])) {
                foreach ($validated['documents'] as $docData) {
                    $url = is_array($docData) ? ($docData['url'] ?? '') : $docData;
                    if (empty($url)) continue;

                    $cat = is_array($docData) ? ($docData['category'] ?? 'other') : 'other';
                    $title = is_array($docData) ? ($docData['name'] ?? ucfirst($cat)) : ucfirst($cat);

                    $project->documents()->updateOrCreate(
                        ['file_url' => $url],
                        [
                            'category'  => $cat,
                            'title'     => $title,
                            'file_name' => is_array($docData) ? ($docData['name'] ?? null) : null,
                            'file_path' => is_array($docData) ? ($docData['path'] ?? null) : null,
                            'file_size' => is_array($docData) ? ($docData['size'] ?? null) : null,
                            'status'    => 'pending',
                        ]
                    );
                }
            }
        });

        return response()->json([
            'success'    => true,
            'message'    => 'Media and documents saved successfully.',
            'project_id' => $project->id,
            'media'      => $project->media()->get(),
            'documents'  => $project->documents()->get(),
        ]);
    }

    /**
     * Upload an individual image, floor plan, or PDF document into normalized models.
     */
    public function uploadMediaFile(Request $request): JsonResponse
    {
        $request->validate([
            'file'       => ['required', 'file', 'max:20480'], // max 20MB
            'category'   => ['nullable', 'string', 'in:photos,floor_plans,brochure,price_list,rera,documents,approval,agreement,other'],
            'project_id' => ['nullable', 'integer', 'exists:project_details,id'],
        ]);

        $file = $request->file('file');
        $category = $request->input('category', 'photos');
        $projectId = $request->input('project_id');

        $ext = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();
        $isImage = str_starts_with($mime, 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        $isDoc = in_array($ext, ['pdf', 'doc', 'docx']);

        if (!$isImage && !$isDoc) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file type. Allowed: JPG, PNG, WEBP, PDF, DOC.',
            ], 422);
        }

        $folder = $projectId ? "projects/{$projectId}/{$category}" : "projects/temp/{$category}";
        $filename = Str::random(16) . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $ext;
        $path = $file->storeAs($folder, $filename, 'public');
        $url = Storage::url($path);

        $fileRecord = [
            'name'      => $file->getClientOriginalName(),
            'path'      => $path,
            'url'       => $url,
            'size'      => $file->getSize(),
            'mime_type' => $mime,
            'category'  => $category,
            'is_image'  => $isImage,
        ];

        // Store into normalized models if project_id is available
        if ($projectId) {
            $project = ProjectDetail::find($projectId);
            if ($project) {
                if ($isDoc || in_array($category, ['brochure', 'price_list', 'rera', 'approval', 'agreement', 'documents'])) {
                    $doc = $project->documents()->create([
                        'category'  => $category === 'documents' ? 'other' : $category,
                        'title'     => $file->getClientOriginalName(),
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_url'  => $url,
                        'mime_type' => $mime,
                        'file_size' => $file->getSize(),
                        'status'    => 'pending',
                    ]);
                    $fileRecord['id'] = $doc->id;
                    $fileRecord['model'] = 'ProjectDocument';
                } else {
                    $isCover = $project->media()->count() === 0;
                    $media = $project->media()->create([
                        'category'   => $category,
                        'title'      => $file->getClientOriginalName(),
                        'file_name'  => $file->getClientOriginalName(),
                        'file_path'  => $path,
                        'file_url'   => $url,
                        'mime_type'  => $mime,
                        'file_size'  => $file->getSize(),
                        'is_cover'   => $isCover,
                        'sort_order' => $project->media()->count(),
                    ]);
                    $fileRecord['id'] = $media->id;
                    $fileRecord['model'] = 'ProjectMedia';
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'file'    => $fileRecord,
        ]);
    }

    /**
     * Step 5: Final Submission / Review publish.
     */
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id'     => ['required', 'integer', 'exists:project_details,id'],
            'terms_accepted' => ['required', 'boolean', 'accepted'],
        ]);

        $project = ProjectDetail::with(['unitConfigurations', 'media', 'documents'])->findOrFail($validated['project_id']);

        if (empty($project->name) || empty($project->builder_name) || empty($project->location)) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete all required project details before submitting.',
            ], 422);
        }

        $project->submitForReview();

        return response()->json([
            'success'      => true,
            'message'      => 'Congratulations! Your project has been submitted for review.',
            'project_id'   => $project->id,
            'status'       => $project->status,
            'redirect_url' => route('builder.projects'),
            'project'      => $project,
        ]);
    }

    /**
     * Save draft at any stage.
     */
    public function saveDraft(Request $request): JsonResponse
    {
        $projectId = $request->input('project_id');
        $project = $projectId ? ProjectDetail::find($projectId) : null;

        $projectData = array_filter([
            'name'                  => $request->input('name') ?: ($project->name ?? 'Untitled Draft Project'),
            'tagline'               => $request->input('tagline', $project->tagline ?? null),
            'builder_name'          => $request->input('builder_name', $project->builder_name ?? 'Draft Builder'),
            'location'              => $request->input('location', $project->location ?? 'Location Pending'),
            'maps_link'             => $request->input('maps_link', $project->maps_link ?? null),
            'project_type'          => $request->input('project_type', $project->project_type ?? 'Residential Apartment'),
            'project_status'        => $request->input('project_status', $project->project_status ?? 'Under Construction'),
            'possession_date'       => $request->input('possession_date', $project->possession_date ?? null),
            'rera_number'           => $request->input('rera_number', $project->rera_number ?? null),
            'towers'                => $request->input('towers', $project->towers ?? null),
            'total_units'           => $request->input('total_units', $project->total_units ?? null),
            'land_area'             => $request->input('land_area', $project->land_area ?? null),
            'smart_bargain_enabled' => $request->has('smart_bargain_enabled') ? (bool) $request->input('smart_bargain_enabled') : ($project->smart_bargain_enabled ?? true),
            'min_expected_price'    => $request->input('min_expected_price', $project->min_expected_price ?? null),
            'target_price'          => $request->input('target_price', $project->target_price ?? null),
            'max_price'             => $request->input('max_price', $project->max_price ?? null),
            'description'           => $request->input('description', $project->description ?? null),
            'status'                => 'draft',
        ], fn ($val) => $val !== null);

        if (!$project) {
            if (auth()->check()) {
                $projectData['user_id'] = auth()->id();
            }
            $project = ProjectDetail::create($projectData);
        } else {
            $project->update($projectData);
        }

        // Amenities
        if ($request->has('amenities')) {
            $amenities = (array) $request->input('amenities', []);
            if ($request->filled('other_amenity')) {
                $amenities[] = trim($request->input('other_amenity'));
            }
            $project->update([
                'amenities' => array_values(array_unique(array_filter(array_map('trim', $amenities)))),
            ]);
        }

        // Highlights
        if ($request->has('highlights')) {
            $highlights = (array) $request->input('highlights', []);
            $project->update([
                'highlights' => array_values(array_unique(array_filter(array_map('trim', $highlights)))),
            ]);
        }

        // Units
        if ($request->has('units') && is_array($request->input('units'))) {
            $units = $request->input('units');
            foreach ($units as $unitData) {
                if (empty($unitData['unit_type']) || empty($unitData['built_up_area']) || empty($unitData['price'])) {
                    continue;
                }

                $builtUp = (float) $unitData['built_up_area'];
                $price = (float) $unitData['price'];

                $project->unitConfigurations()->updateOrCreate(
                    ['id' => $unitData['id'] ?? null],
                    [
                        'unit_type'       => trim($unitData['unit_type']),
                        'built_up_area'   => $builtUp,
                        'carpet_area'     => (float) ($unitData['carpet_area'] ?? $builtUp),
                        'price'           => $price,
                        'price_per_sqft'  => $builtUp > 0 ? round($price / $builtUp, 2) : 0,
                        'available_units' => (int) ($unitData['available_units'] ?? 1),
                        'floor_range'     => $unitData['floor_range'] ?? 'All Floors',
                        'show_floor_plan' => !empty($unitData['show_floor_plan']),
                    ]
                );
            }
        }

        // Video / Virtual Tour in normalized media
        if ($request->filled('video_url')) {
            $project->media()->updateOrCreate(
                ['category' => 'video'],
                ['file_url' => trim($request->input('video_url')), 'title' => 'Project Video', 'file_name' => 'Project Video']
            );
        }
        if ($request->filled('virtual_tour_url')) {
            $project->media()->updateOrCreate(
                ['category' => 'virtual_tour'],
                ['file_url' => trim($request->input('virtual_tour_url')), 'title' => 'Virtual Tour', 'file_name' => 'Virtual Tour']
            );
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Project draft saved successfully.',
            'project_id' => $project->id,
            'project'    => $project->load(['unitConfigurations', 'media', 'documents']),
        ]);
    }

    /**
     * Fetch project details with unit configurations, normalized media, and documents.
     */
    public function show(int $id): JsonResponse
    {
        $project = ProjectDetail::with(['unitConfigurations', 'media', 'documents'])->findOrFail($id);

        return response()->json([
            'success'   => true,
            'project'   => $project,
            'media'     => $project->media,
            'documents' => $project->documents,
        ]);
    }
}
