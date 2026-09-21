@extends('layouts.builder')

@section('content')
    <div class="page-toolbar listings-toolbar">
        @include('builder.partials.toolbar-search', ['placeholder' => 'Search units...'])

        <select class="toolbar-select" aria-label="Filter by project" data-listing-project>
            @foreach ($listingProjects as $option)
                <option>{{ $option }}</option>
            @endforeach
        </select>

        <div class="toolbar-cluster">
            <div class="status-tabs" data-filter-group="listings">
                @foreach ($listingFilters as $index => $tab)
                    <button
                        type="button"
                        class="status-tab {{ $index === 0 ? 'is-active' : '' }}"
                        data-filter-tab
                        data-listing-filter="{{ strtolower($tab) }}"
                    >{{ $tab }}</button>
                @endforeach
            </div>

            <button type="button" class="btn btn-primary" data-toast="Add Unit form coming soon">
                {!! \App\Support\Icon::svg('plus') !!} Add Unit
            </button>
        </div>
    </div>

    <div class="card table-wrap">
        <table class="data-table listings-table">
            <thead>
                <tr>
                    <th>Unit ID</th>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Leads</th>
                    <th>Views</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listings as $listing)
                    @include('builder.partials.listing-row', ['listing' => $listing])
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
