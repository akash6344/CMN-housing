<div class="chart-legend">
    @foreach ($items as $item)
        <span class="chart-legend-item">
            <i style="background: {{ $item['color'] }}"></i>
            {{ $item['label'] ?? $item['key'] }}
        </span>
    @endforeach
</div>
