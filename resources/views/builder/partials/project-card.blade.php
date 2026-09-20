<article class="card project-card">
    <div class="project-card-cover">
        <span class="chip {{ $project['statusClass'] }}">{{ $project['status'] }}</span>
        <div class="project-card-glyph">{!! \App\Support\Icon::svg('building') !!}</div>
    </div>

    <div class="project-card-body">
        <h3 class="project-card-name">{{ $project['name'] }}</h3>
        <div class="project-card-location">
            {!! \App\Support\Icon::svg('map') !!}
            <span>{{ $project['location'] }}</span>
        </div>

        <div class="project-metrics">
            <div>
                <span>Total Units</span>
                <strong>{{ $project['totalUnits'] }}</strong>
            </div>
            <div>
                <span>Sold</span>
                <strong class="text-success">{{ $project['sold'] }}</strong>
            </div>
            <div>
                <span>Available</span>
                <strong class="text-teal">{{ $project['available'] }}</strong>
            </div>
        </div>

        <div class="project-progress">
            <div class="project-progress-head">
                <span>Sales Progress</span>
                <strong>{{ $project['progress'] }}%</strong>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: {{ $project['progress'] }}%"></div>
            </div>
        </div>

        <div class="project-card-foot">
            <div class="project-leads">
                {!! \App\Support\Icon::svg('user') !!}
                <span>{{ $project['leads'] }} leads</span>
                @if ($project['leads'] > 0)
                    {!! \App\Support\Icon::svg('trendUp') !!}
                @endif
            </div>
            <button type="button" class="icon-ghost" aria-label="More options" data-toast="Project options">
                {!! \App\Support\Icon::svg('more') !!}
            </button>
        </div>

        <div class="project-rera">RERA: {{ $project['rera'] }}</div>
    </div>
</article>
