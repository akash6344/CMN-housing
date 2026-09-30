<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\ProjectDetail;
use App\Support\DemoDashboardData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = DemoDashboardData::all();

        return view('builder.dashboard', array_merge($data, [
            'pageTitle' => 'Dashboard Overview',
            'pageSub' => 'Welcome back, '.$data['builder']['name'],
            'active' => 'overview',
        ]));
    }

    public function projects(Request $request): View
    {
        $data = DemoDashboardData::projects();

        // Load real projects from MySQL
        $dbProjects = ProjectDetail::with('unitConfigurations')->latest()->get();

        $mappedProjects = $dbProjects->map(function ($p) {
            $totalUnits = $p->total_units ?: ($p->unitConfigurations->sum('available_units') ?: 0);
            $available = $p->unitConfigurations->sum('available_units') ?: $totalUnits;
            $sold = max(0, $totalUnits - $available);
            $progress = $totalUnits > 0 ? round(($sold / $totalUnits) * 100) : 0;

            return [
                'id'          => $p->id,
                'name'        => $p->name,
                'location'    => $p->location,
                'status'      => $p->status_label,
                'statusClass' => $p->status_class,
                'totalUnits'  => $totalUnits,
                'sold'        => $sold,
                'available'   => $available,
                'progress'    => $progress,
                'leads'       => 0,
                'rera'        => $p->rera_number ?: 'Pending',
                'price'       => $p->starting_price_label,
            ];
        })->toArray();

        // Prepend real projects before demo projects so user sees their projects first
        $data['projects'] = array_merge($mappedProjects, $data['projects']);

        return view('builder.projects', array_merge($data, [
            'pageTitle' => 'Projects & Inventory',
            'pageSub'   => 'Manage your real estate projects',
            'active'    => 'projects',
        ]));
    }

    public function leads(): View
    {
        $data = DemoDashboardData::leadsPage();

        return view('builder.leads', array_merge($data, [
            'pageTitle' => 'Lead Management',
            'pageSub' => 'Track and manage your property inquiries',
            'active' => 'leads',
        ]));
    }

    public function listings(): View
    {
        $data = DemoDashboardData::listings();

        return view('builder.listings', array_merge($data, [
            'pageTitle' => 'Property Listings',
            'pageSub' => 'Manage your unit inventory',
            'active' => 'listings',
        ]));
    }

    public function settings(): View
    {
        $data = DemoDashboardData::settings();

        return view('builder.settings', array_merge($data, [
            'pageTitle' => 'Settings',
            'pageSub' => 'Manage your account and preferences',
            'active' => 'settings',
        ]));
    }

    public function notifications(): View
    {
        $data = DemoDashboardData::notificationsPage();

        return view('builder.notifications', array_merge($data, [
            'pageTitle' => 'Notifications',
            'pageSub' => $data['unreadCount'].' unread notifications',
            'active' => 'notifications',
        ]));
    }

    public function ads(): View
    {
        $data = DemoDashboardData::ads();

        return view('builder.ads', array_merge($data, [
            'pageTitle' => 'Ads & Promotions',
            'pageSub' => 'Manage your marketing campaigns',
            'active' => 'ads',
        ]));
    }

    public function analytics(): View
    {
        $data = DemoDashboardData::analytics();

        return view('builder.analytics', array_merge($data, [
            'pageTitle' => 'Analytics & Reports',
            'pageSub' => 'Track your performance metrics',
            'active' => 'analytics',
        ]));
    }

    public function documents(): View
    {
        $data = DemoDashboardData::documents();

        return view('builder.documents', array_merge($data, [
            'pageTitle' => 'Documents & Compliance',
            'pageSub' => 'Manage project documentation',
            'active' => 'documents',
        ]));
    }

    public function postProject(Request $request): View
    {
        $data = DemoDashboardData::postProject();
        $projectId = $request->query('project_id');

        if ($projectId) {
            $project = ProjectDetail::with('unitConfigurations')->find($projectId);
            if ($project) {
                $data['projectId'] = $project->id;
                $data['projectForm'] = [
                    'name'            => $project->name,
                    'tagline'         => $project->tagline,
                    'builder'         => $project->builder_name,
                    'location'        => $project->location,
                    'mapsLink'        => $project->maps_link,
                    'type'            => $project->project_type,
                    'status'          => $project->project_status,
                    'possession'      => $project->possession_date?->format('Y-m-d') ?? '',
                    'possessionLabel' => $project->possession_date?->format('M Y') ?? '',
                    'rera'            => $project->rera_number,
                    'towers'          => $project->towers,
                    'totalUnits'      => $project->total_units,
                    'landArea'        => $project->land_area,
                    'description'     => $project->description,
                ];

                $data['unitTypes'] = $project->unitConfigurations->map(fn($u) => [
                    'id'         => $u->id,
                    'type'       => $u->unit_type,
                    'builtUp'    => $u->built_up_area,
                    'carpet'     => $u->carpet_area,
                    'price'      => $u->price,
                    'priceLabel' => $u->price_label,
                    'perSqft'    => $u->price_per_sqft,
                    'available'  => $u->available_units,
                    'floors'     => $u->floor_range,
                    'showPlan'   => (bool) $u->show_floor_plan,
                ])->toArray();

                $selectedAmenities = $project->amenities ?? [];
                $data['amenitiesList'] = array_map(function ($item) use ($selectedAmenities) {
                    $item['checked'] = in_array($item['label'], $selectedAmenities, true);
                    return $item;
                }, $data['amenitiesList']);

                $data['mediaSummary'] = [
                    'images'    => $project->photos()->count(),
                    'plans2d'   => $project->media()->where('category', 'floor_plan_2d')->count(),
                    'plans3d'   => $project->media()->where('category', 'floor_plan_3d')->count(),
                    'brochure'  => $project->documents()->where('category', 'brochure')->exists(),
                    'priceList' => $project->documents()->where('category', 'price_list')->exists(),
                    'reraCert'  => $project->documents()->where('category', 'rera')->exists(),
                    'video'     => $project->video_url ?? '',
                ];

                $data['highlights'] = $project->highlights ?? [];
                $data['description'] = $project->description ?? '';
                $data['smartBargain'] = [
                    'enabled' => (bool) $project->smart_bargain_enabled,
                    'min'     => $project->min_expected_price,
                    'target'  => $project->target_price,
                    'max'     => $project->max_price,
                ];
                $data['preview'] = [
                    'name'     => $project->name,
                    'location' => $project->location,
                    'price'    => $project->starting_price_label,
                    'status'   => $project->status_label,
                    'meta'     => $project->project_type,
                ];
            }
        }

        return view('builder.post-project', array_merge($data, [
            'pageTitle' => 'Post New Project',
            'pageSub'   => 'List your project and reach thousands of verified buyers on CMNHousing.',
            'active'    => 'projects',
            'projectId' => $data['projectId'] ?? null,
        ]));
    }

    public function projectPreview(Request $request): View
    {
        $projectId = $request->query('project_id') ?? $request->query('id');

        if ($projectId) {
            $project = ProjectDetail::with('unitConfigurations')->find($projectId);
            if ($project) {
                $unitConfigs = [];
                foreach ($project->unitConfigurations as $idx => $unit) {
                    $unitConfigs[] = [
                        'type'    => $unit->unit_type,
                        'range'   => $unit->price_label,
                        'active'  => $idx === 0,
                        'sizes'   => [
                            ['area' => $unit->built_up_area . ' Sq.Ft', 'price' => $unit->price_label],
                        ],
                        'builtUp' => $unit->built_up_area . ' Sq.Ft',
                        'carpet'  => $unit->carpet_area . ' Sq.Ft',
                        'rooms'   => $unit->room_configurations ?? [
                            ['name' => 'Living / Drawing', 'size' => "15'0\" × 11'0\""],
                            ['name' => 'Master Bedroom', 'size' => "12'0\" × 11'0\""],
                            ['name' => 'Kitchen', 'size' => "10'0\" × 8'0\""],
                            ['name' => 'Balcony', 'size' => "8'0\" × 4'0\""],
                        ],
                    ];
                }

                $badges = [
                    ['label' => 'RERA Registered', 'class' => 'chip-available'],
                    ['label' => 'Verified Builder', 'class' => 'chip-approval'],
                ];
                if ($project->smart_bargain_enabled) {
                    $badges[] = ['label' => 'Smart Bargain Enabled', 'class' => 'chip-live'];
                }

                $locationParts = array_map('trim', explode(',', $project->location));
                $city = end($locationParts) ?: 'City';
                $locality = count($locationParts) > 1 ? $locationParts[0] : $project->location;

                $defaultPreview = DemoDashboardData::projectPreview();

                $previewData = [
                    'project' => [
                        'id'            => $project->id,
                        'name'          => $project->name,
                        'tagline'       => $project->tagline ?? 'Luxury Living. Smarter Prices.',
                        'builder'       => $project->builder_name,
                        'location'      => $project->location,
                        'mapsLink'      => $project->maps_link ?? '#',
                        'type'          => $project->project_type,
                        'status'        => $project->project_status,
                        'possession'    => $project->possession_date?->format('Y-m-d') ?? '',
                        'possessionLabel' => $project->possession_date?->format('M Y') ?? 'Dec 2026',
                        'rera'          => $project->rera_number ?? 'Pending',
                        'towers'        => (string) ($project->towers ?? '1'),
                        'totalUnits'    => (string) ($project->total_units ?? $project->unitConfigurations->sum('available_units')),
                        'landArea'      => $project->land_area ?? 'N/A',
                        'featured'      => true,
                        'photoCount'    => count($project->media_photos ?? [1, 2, 3]),
                        'basePrice'     => $project->unitConfigurations->first()?->price_per_sqft ? '₹' . number_format($project->unitConfigurations->first()->price_per_sqft) . '/sq.ft' : '₹5,000/sq.ft',
                        'startingPrice' => $project->starting_price_label,
                        'city'          => $city,
                        'locality'      => $locality,
                    ],
                    'badges'        => $badges,
                    'sectionTabs'   => ['Overview', 'Unit Plans', 'Floor Plans', 'Amenities', 'Location', 'Price & Payment', 'Builder', 'Reviews'],
                    'unitConfigs'   => !empty($unitConfigs) ? $unitConfigs : $defaultPreview['unitConfigs'],
                    'highlights'    => !empty($project->highlights) ? $project->highlights : [
                        'Prime location in ' . $project->location,
                        'Smart Bargain enabled for transparent negotiation',
                        'RERA approved project (' . ($project->rera_number ?? 'In Process') . ')',
                    ],
                    'description'   => $project->description ?: 'Welcome to ' . $project->name . ', a landmark development offering world-class living spaces thoughtfully planned with modern amenities.',
                    'amenitiesStrip' => !empty($project->amenities) ? $project->amenities : $defaultPreview['amenitiesStrip'],
                    'galleryTiles'   => $defaultPreview['galleryTiles'],
                    'pageTitle'      => $project->name,
                ];

                return view('site.project-preview', $previewData);
            }
        }

        $data = DemoDashboardData::projectPreview();

        return view('site.project-preview', array_merge($data, [
            'pageTitle' => $data['project']['name'],
        ]));
    }

    public function comingSoon(string $page): View
    {
        $data = DemoDashboardData::shell();

        $labels = collect($data['navItems'])
            ->concat($data['footItems'])
            ->pluck('label', 'id');

        $title = $labels[$page] ?? ucfirst($page);

        return view('builder.coming-soon', [
            'builder' => $data['builder'],
            'navItems' => $data['navItems'],
            'footItems' => $data['footItems'],
            'pageTitle' => $title,
            'pageSub' => 'This section is coming soon',
            'active' => $page,
            'sectionName' => $title,
        ]);
    }
}
