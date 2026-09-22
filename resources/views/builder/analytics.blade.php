@extends('layouts.builder')

@section('content')
    <div class="page-toolbar analytics-toolbar">
        <div class="status-tabs" data-filter-group="analytics-range">
            @foreach ($analyticsRanges as $index => $tab)
                <button
                    type="button"
                    class="status-tab {{ $tab === '30 Days' ? 'is-active' : '' }}"
                    data-filter-tab
                    data-toast="Showing {{ $tab }}"
                >{{ $tab }}</button>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline" data-toast="Export started">
            {!! \App\Support\Icon::svg('download') !!} Export Report
        </button>
    </div>

    <div class="stat-grid analytics-stat-grid">
        @foreach ($analyticsStats as $stat)
            @include('builder.partials.stat-card', ['stat' => $stat])
        @endforeach
    </div>

    <div class="analytics-grid-top">
        <article class="card chart-card">
            <h3 class="chart-card-title">Lead Trend</h3>
            @include('builder.partials.chart-area', ['data' => $leadTrend])
        </article>

        <article class="card chart-card">
            <h3 class="chart-card-title">Conversion Funnel</h3>
            @include('builder.partials.chart-grouped-bars', ['data' => $conversionFunnel])
        </article>
    </div>

    <div class="analytics-grid-bottom">
        <article class="card chart-card">
            <h3 class="chart-card-title">Project Performance</h3>
            @include('builder.partials.chart-h-bars', ['data' => $projectPerformance])
        </article>

        <article class="card chart-card">
            <h3 class="chart-card-title">City-wise Demand</h3>
            @include('builder.partials.chart-donut', ['items' => $cityDemand, 'title' => 'City-wise Demand'])
        </article>

        <article class="card chart-card">
            <h3 class="chart-card-title">Bargain Success Rate</h3>
            @include('builder.partials.chart-donut', ['items' => $bargainRate, 'title' => 'Bargain Success Rate'])
        </article>
    </div>
@endsection
