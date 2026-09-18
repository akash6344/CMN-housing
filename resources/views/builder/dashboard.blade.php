@extends('layouts.builder')

@section('content')
    <div class="stat-grid">
        @foreach ($stats as $stat)
            @include('builder.partials.stat-card', ['stat' => $stat])
        @endforeach
    </div>

    <div class="overview-grid">
        <div class="overview-col">
            <section>
                <div class="section-head">
                    <h2>Recent Leads</h2>
                    <a class="link" href="{{ route('builder.leads') }}">View All</a>
                </div>
                <div class="leads-row">
                    @foreach ($leads as $lead)
                        @include('builder.partials.lead-card', ['lead' => $lead])
                    @endforeach
                </div>
            </section>

            <section>
                <div class="section-head">
                    <h2>Active Bargain Deals</h2>
                    <a class="link" href="{{ route('builder.bargain') }}">View All</a>
                </div>
                <div class="deals-row">
                    @foreach ($deals as $deal)
                        @include('builder.partials.deal-card', ['deal' => $deal])
                    @endforeach
                </div>
                <div class="banner">
                    <div class="banner-icon">{!! \App\Support\Icon::svg('alert') !!}</div>
                    <div class="banner-copy">
                        <strong>{{ $banner['title'] }}</strong>
                        <span>{{ $banner['text'] }}</span>
                    </div>
                    <a class="btn btn-outline btn-sm" href="{{ route('builder.projects') }}">View Details</a>
                </div>
            </section>
        </div>

        <div class="overview-col">
            <aside class="card activity-card">
                <div class="section-head"><h2>Recent Activity</h2></div>
                <div class="activity-list">
                    @foreach ($activity as $item)
                        @include('builder.partials.activity-item', ['item' => $item])
                    @endforeach
                </div>
            </aside>

            <aside class="card hot-card">
                <div class="section-head"><h2>Hot Units</h2></div>
                <div class="hot-list">
                    @foreach ($hotUnits as $unit)
                        @include('builder.partials.hot-unit', ['unit' => $unit])
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
@endsection
