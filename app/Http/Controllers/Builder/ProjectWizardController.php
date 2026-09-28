<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\ProjectDetail;
use App\Models\UnitConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProjectWizardController extends Controller
{
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
     * Save draft at any stage (supports partial data from Step 1, 2, or 3).
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

        // If amenities are provided, update them
        if ($request->has('amenities')) {
            $amenities = (array) $request->input('amenities', []);
            if ($request->filled('other_amenity')) {
                $amenities[] = trim($request->input('other_amenity'));
            }
            $project->update([
                'amenities' => array_values(array_unique(array_filter(array_map('trim', $amenities)))),
            ]);
        }

        // If units are provided, save them
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

        return response()->json([
            'success'    => true,
            'message'    => 'Project draft saved successfully.',
            'project_id' => $project->id,
            'project'    => $project->load('unitConfigurations'),
        ]);
    }

    /**
     * Fetch project details with unit configurations.
     */
    public function show(int $id): JsonResponse
    {
        $project = ProjectDetail::with('unitConfigurations')->findOrFail($id);

        return response()->json([
            'success' => true,
            'project' => $project,
        ]);
    }
}
