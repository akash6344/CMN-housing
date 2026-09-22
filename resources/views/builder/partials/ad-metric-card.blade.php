<article class="card ad-metric-card">
    <div class="ad-metric-icon icon-{{ $metric['tone'] }}">
        {!! \App\Support\Icon::svg($metric['icon']) !!}
    </div>
    <div>
        <div class="metric-label">{{ $metric['label'] }}</div>
        <div class="metric-value">{{ $metric['value'] }}</div>
    </div>
</article>
