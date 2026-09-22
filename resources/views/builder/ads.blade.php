@extends('layouts.builder')

@section('content')
    <div class="stat-grid ad-metric-grid">
        @foreach ($adMetrics as $metric)
            @include('builder.partials.ad-metric-card', ['metric' => $metric])
        @endforeach
    </div>

    <div class="page-toolbar campaigns-toolbar">
        <div class="status-tabs" data-filter-group="campaigns">
            @foreach ($campaignFilters as $index => $tab)
                <button
                    type="button"
                    class="status-tab {{ $index === 0 ? 'is-active' : '' }}"
                    data-filter-tab
                    data-campaign-filter="{{ $index === 0 ? 'all' : strtolower(str_replace(' Campaigns', '', $tab)) }}"
                >{{ $tab }}</button>
            @endforeach
        </div>

        <button type="button" class="btn btn-primary" data-toast="Create Campaign form coming soon">
            {!! \App\Support\Icon::svg('plus') !!} Create Campaign
        </button>
    </div>

    <div class="campaign-grid" data-campaign-grid>
        @foreach ($campaigns as $campaign)
            @include('builder.partials.campaign-card', ['campaign' => $campaign])
        @endforeach
    </div>
@endsection
