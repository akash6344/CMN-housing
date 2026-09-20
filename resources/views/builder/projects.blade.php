@extends('layouts.builder')

@section('content')
    <div class="page-toolbar">
        @include('builder.partials.toolbar-search', ['placeholder' => 'Search projects...'])

        <button type="button" class="icon-btn" aria-label="Filter" data-toast="Filters coming soon">
            {!! \App\Support\Icon::svg('filter') !!}
        </button>

        <div class="toolbar-cluster">
            @include('builder.partials.status-tabs', ['tabs' => $projectFilters, 'group' => 'projects'])

            <div class="view-toggle" data-view-toggle>
                <button type="button" class="view-toggle-btn is-active" data-view="grid" aria-label="Grid view">
                    {!! \App\Support\Icon::svg('grid') !!}
                </button>
                <button type="button" class="view-toggle-btn" data-view="list" aria-label="List view">
                    {!! \App\Support\Icon::svg('list') !!}
                </button>
            </div>

            <button type="button" class="btn btn-primary" id="add-project-page">
                {!! \App\Support\Icon::svg('plus') !!} Add Project
            </button>
        </div>
    </div>

    <div class="project-grid" data-project-grid>
        @foreach ($projects as $project)
            @include('builder.partials.project-card', ['project' => $project])
        @endforeach
    </div>
@endsection
