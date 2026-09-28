@extends('layouts.site')

@section('content')
    <div class="ppage" data-project-preview>
        <div class="ppage-top">
            <nav class="ppage-crumbs" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Home</a>
                <span>/</span>
                <a href="#">New Projects</a>
                <span>/</span>
                <a href="#">{{ $project['city'] }}</a>
                <span>/</span>
                <a href="#">{{ $project['locality'] }}</a>
                <span>/</span>
                <span>{{ $project['name'] }}</span>
            </nav>
            <div class="ppage-actions">
                <button type="button" class="btn btn-outline btn-sm" data-toast="Share link copied">
                    {!! \App\Support\Icon::svg('share') !!} Share
                </button>
                <button type="button" class="btn btn-outline btn-sm" data-toast="Saved to wishlist">
                    {!! \App\Support\Icon::svg('heart') !!} Save
                </button>
                <button type="button" class="btn btn-outline btn-sm" data-toast="Added to compare">
                    {!! \App\Support\Icon::svg('compare') !!} Compare
                </button>
            </div>
        </div>

        <section class="ppage-hero">
            <div class="ppage-hero-copy">
                <div class="ppage-title-row">
                    <h1>{{ $project['name'] }}</h1>
                    @if (!empty($project['featured']))
                        <span class="chip chip-featured">Featured Project</span>
                    @endif
                </div>
                <p class="ppage-builder">By <a href="#">{{ $project['builder'] }}</a></p>
                <p class="ppage-location">
                    {!! \App\Support\Icon::svg('map') !!}
                    {{ $project['location'] }}
                </p>
                <div class="ppage-badges">
                    @foreach ($badges as $badge)
                        <span class="chip {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    @endforeach
                </div>
            </div>
            <p class="ppage-tagline">{{ $project['tagline'] }}</p>
        </section>

        <section class="ppage-gallery">
            <div class="ppage-gallery-main">
                <button type="button" class="ppage-gallery-all" data-toast="Gallery coming soon">
                    View All Photos ({{ $project['photoCount'] }})
                </button>
            </div>
            <div class="ppage-gallery-side">
                @foreach ($galleryTiles as $tile)
                    <div class="ppage-gallery-tile {{ !empty($tile['hasPlay']) ? 'has-play' : '' }}">
                        @if (!empty($tile['hasPlay']))
                            <span class="ppage-play">{!! \App\Support\Icon::svg('play') !!}</span>
                        @endif
                        <span>{{ $tile['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <nav class="ppage-tabs" aria-label="Project sections">
            @foreach ($sectionTabs as $tab)
                <button type="button" class="ppage-tab {{ $loop->first ? 'is-active' : '' }}" data-ppage-tab>
                    {{ $tab }}
                </button>
            @endforeach
        </nav>

        <div class="ppage-layout">
            <div class="ppage-main">
                <section class="ppage-section">
                    <h2>Unit Configuration & Pricing</h2>
                    <div class="ppage-bhk-row" data-bhk-tabs>
                        @foreach ($unitConfigs as $unit)
                            <button
                                type="button"
                                class="ppage-bhk-card {{ !empty($unit['active']) ? 'is-active' : '' }}"
                                data-bhk-tab="{{ $loop->index }}"
                            >
                                <strong>{{ $unit['type'] }}</strong>
                                <span>{{ $unit['range'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    @foreach ($unitConfigs as $unit)
                        <div class="ppage-bhk-panel" data-bhk-panel="{{ $loop->index }}" @if (empty($unit['active'])) hidden @endif>
                            <div class="ppage-sizes-head">
                                <h3>Available Sizes ({{ $unit['type'] }})</h3>
                            </div>
                            <div class="ppage-sizes">
                                @foreach ($unit['sizes'] as $size)
                                    <button type="button" class="ppage-size-card {{ $loop->first ? 'is-active' : '' }}" data-size-card>
                                        <strong>{{ $size['area'] }}</strong>
                                        <span>{{ $size['price'] }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <div class="ppage-plan">
                                <div class="ppage-plan-toggle" data-plan-toggle>
                                    <button type="button" class="ppage-plan-btn" data-plan-view="2d">2D Floor Plan</button>
                                    <button type="button" class="ppage-plan-btn is-active" data-plan-view="3d">3D Floor Plan</button>
                                </div>
                                <div class="ppage-plan-body">
                                    <div class="ppage-plan-visual is-3d" data-plan-visual></div>
                                    <div class="ppage-plan-legend">
                                        <h4>{{ $unit['type'] }} – {{ $unit['builtUp'] }}</h4>
                                        <ol>
                                            @foreach ($unit['rooms'] as $room)
                                                <li>
                                                    <span>{{ $room['name'] }}</span>
                                                    <em>{{ $room['size'] }}</em>
                                                </li>
                                            @endforeach
                                        </ol>
                                        <div class="ppage-plan-stats">
                                            <div><span>Built-up Area</span><strong>{{ $unit['builtUp'] }}</strong></div>
                                            <div><span>Carpet Area</span><strong>{{ $unit['carpet'] }}</strong></div>
                                        </div>
                                        <button type="button" class="btn btn-outline" data-toast="Floor plans coming soon">
                                            View All Floor Plans
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </section>

                <section class="ppage-amenities">
                    @foreach ($amenitiesStrip as $item)
                        <div class="ppage-amenity">
                            {!! \App\Support\Icon::svg('checkSimple') !!}
                            <span>{{ $item }}</span>
                        </div>
                    @endforeach
                </section>
            </div>

            <aside class="ppage-aside">
                <article class="card ppage-bargain">
                    <div class="ppage-bargain-kicker">
                        {!! \App\Support\Icon::svg('handshake') !!}
                        SMART BARGAIN AVAILABLE
                    </div>
                    <p>AI Powered • Transparent • Better Deal</p>
                    <button type="button" class="btn btn-primary" data-toast="Smart Bargain coming soon">
                        Make Smart Bargain Offer {!! \App\Support\Icon::svg('arrowRight') !!}
                    </button>
                    <div class="ppage-bargain-feats">
                        <span>Best Price Range</span>
                        <span>AI Insights</span>
                        <span>Limited Time Offers</span>
                        <span>Direct Builder Deal</span>
                    </div>
                </article>

                <article class="card ppage-price-card">
                    <div class="ppage-price-row">
                        <div>
                            <span>Starting Price</span>
                            <strong>{{ $project['startingPrice'] }}</strong>
                        </div>
                        <div>
                            <span>Base Price</span>
                            <strong>{{ $project['basePrice'] }}</strong>
                        </div>
                    </div>
                    <button type="button" class="btn btn-enquire" data-toast="Enquiry sent">
                        {!! \App\Support\Icon::svg('phone') !!} Enquire Now
                    </button>
                    <div class="ppage-price-actions">
                        <button type="button" class="btn btn-outline btn-sm" data-toast="Site visit requested">
                            {!! \App\Support\Icon::svg('calendar') !!} Request Site Visit
                        </button>
                        <button type="button" class="btn btn-outline btn-sm" data-toast="Callback requested">
                            {!! \App\Support\Icon::svg('phone') !!} Get Call Back
                        </button>
                    </div>
                </article>

                <article class="card ppage-side-card">
                    <h3>Project Highlights</h3>
                    <ul>
                        @foreach ($highlights as $item)
                            <li>
                                {!! \App\Support\Icon::svg('checkSimple') !!}
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </article>

                <article class="card ppage-side-card">
                    <h3>Project Details</h3>
                    <dl class="ppage-details">
                        <div><dt>Project Type</dt><dd>{{ $project['type'] }}</dd></div>
                        <div><dt>Project Status</dt><dd><span class="chip chip-hold">{{ $project['status'] }}</span></dd></div>
                        <div><dt>Possession Date</dt><dd>{{ $project['possessionLabel'] }}</dd></div>
                        <div>
                            <dt>RERA Registration No.</dt>
                            <dd>
                                {{ $project['rera'] }}
                                <button type="button" class="link-btn" data-toast="Opening RERA site">View on RERA Website</button>
                            </dd>
                        </div>
                        <div><dt>No. of Towers</dt><dd>{{ $project['towers'] }}</dd></div>
                        <div><dt>Total Units</dt><dd>{{ $project['totalUnits'] }}</dd></div>
                        <div><dt>Land Area</dt><dd>{{ $project['landArea'] }}</dd></div>
                    </dl>
                </article>
            </aside>
        </div>

        <section class="ppage-cta">
            <div>
                <strong>Get the Best Price with Smart Bargain</strong>
                <p>AI-powered offers for a transparent, better deal.</p>
            </div>
            <button type="button" class="btn btn-white" data-toast="Smart Bargain coming soon">
                Start Smart Bargain Now {!! \App\Support\Icon::svg('arrowRight') !!}
            </button>
        </section>

        <div class="ppage-back">
            <a href="{{ route('builder.projects.create') }}">{!! \App\Support\Icon::svg('arrowLeft') !!} Back to Post Project</a>
        </div>
    </div>
@endsection
