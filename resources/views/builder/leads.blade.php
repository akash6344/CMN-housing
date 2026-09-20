@extends('layouts.builder')

@section('content')
    <div class="stat-grid metric-grid">
        @foreach ($leadMetrics as $metric)
            @include('builder.partials.metric-card', ['metric' => $metric])
        @endforeach
    </div>

    <div class="page-toolbar leads-toolbar">
        @include('builder.partials.toolbar-search', ['placeholder' => 'Search leads...'])

        <select class="toolbar-select" aria-label="Filter by project">
            @foreach ($leadProjects as $option)
                <option>{{ $option }}</option>
            @endforeach
        </select>

        <div class="toolbar-cluster">
            @include('builder.partials.status-tabs', ['tabs' => $leadFilters, 'group' => 'leads'])

            <button type="button" class="btn btn-outline" data-toast="Export started">
                {!! \App\Support\Icon::svg('download') !!} Export
            </button>
        </div>
    </div>

    <div class="card table-wrap">
        <table class="data-table leads-table">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Project / Unit</th>
                    <th>Status</th>
                    <th>Source</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($managementLeads as $lead)
                    @include('builder.partials.lead-row', ['lead' => $lead])
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
