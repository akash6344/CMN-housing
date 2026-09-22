<article class="card campaign-card" data-campaign-card data-status="{{ strtolower($campaign['status']) }}">
    <div class="campaign-card-head">
        <div>
            <h3 class="campaign-name">{{ $campaign['name'] }}</h3>
            <p class="campaign-meta">{{ $campaign['meta'] }}</p>
        </div>
        <div class="campaign-card-tools">
            <span class="chip {{ $campaign['statusClass'] }}">{{ $campaign['status'] }}</span>
            <button type="button" class="icon-ghost" data-toast="Campaign options" aria-label="More options">
                {!! \App\Support\Icon::svg('more') !!}
            </button>
        </div>
    </div>

    <div class="campaign-metrics">
        <div>
            <span>Impressions</span>
            <strong>{{ $campaign['impressions'] }}</strong>
        </div>
        <div>
            <span>Clicks</span>
            <strong>{{ $campaign['clicks'] }}</strong>
        </div>
        <div>
            <span>CTR</span>
            <strong class="text-teal">{{ $campaign['ctr'] }}</strong>
        </div>
    </div>

    <div class="campaign-budget">
        <div class="campaign-budget-head">
            <span>Budget Used</span>
            <strong>{{ $campaign['spent'] }} / {{ $campaign['budget'] }}</strong>
        </div>
        <div class="progress-track">
            <div class="progress-fill" style="width: {{ $campaign['progress'] }}%"></div>
        </div>
    </div>

    <div class="campaign-dates">
        <span>{{ $campaign['start'] }}</span>
        {!! \App\Support\Icon::svg('arrowRight') !!}
        <span>{{ $campaign['end'] }}</span>
    </div>
</article>
