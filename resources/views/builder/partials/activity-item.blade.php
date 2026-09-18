<div class="activity-item">
    <div class="activity-icon icon-{{ $item['tone'] }}">
        {!! \App\Support\Icon::svg($item['icon']) !!}
    </div>
    <div>
        <div class="activity-title">{{ $item['title'] }}</div>
        <div class="activity-desc">{{ $item['desc'] }}</div>
        <div class="activity-time">
            {!! \App\Support\Icon::svg('clock') !!} {{ $item['time'] }}
        </div>
    </div>
</div>
