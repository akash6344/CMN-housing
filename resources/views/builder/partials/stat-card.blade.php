<article class="card stat-card">
    <div class="stat-label">{{ $stat['label'] }}</div>
    <div class="stat-icon icon-{{ $stat['tone'] }}">
        {!! \App\Support\Icon::svg($stat['icon']) !!}
    </div>
    <div class="stat-value">{{ $stat['value'] }}</div>
    <div class="trend {{ $stat['dir'] }}">{{ $stat['trend'] }}</div>
</article>
