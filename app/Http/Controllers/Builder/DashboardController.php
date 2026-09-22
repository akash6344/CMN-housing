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

    public function projects(): View
    {
        $data = DemoDashboardData::projects();

        return view('builder.projects', array_merge($data, [
            'pageTitle' => 'Projects & Inventory',
            'pageSub' => 'Manage your real estate projects',
            'active' => 'projects',
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
