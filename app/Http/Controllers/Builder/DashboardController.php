<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Support\DemoDashboardData;
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

    public function comingSoon(string $page): View
    {
        $data = DemoDashboardData::all();

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
