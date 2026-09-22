@php
    $donut = \App\Support\ChartHelper::donutSegments($items);
@endphp
<div class="chart-donut-wrap">
    <svg viewBox="0 0 140 140" class="chart-donut" role="img" aria-label="{{ $title ?? 'Donut chart' }}">
        @foreach ($donut['segments'] as $segment)
            <circle
                cx="70"
                cy="70"
                r="{{ $donut['radius'] }}"
                fill="none"
                stroke="{{ $segment['color'] }}"
                stroke-width="{{ $donut['stroke'] }}"
                stroke-dasharray="{{ $segment['dash'] }}"
                stroke-dashoffset="{{ $segment['offset'] }}"
                transform="rotate(-90 70 70)"
            />
        @endforeach
    </svg>
    @include('builder.partials.chart-legend', ['items' => $items])
</div>
