@php
    $chart = \App\Support\ChartHelper::areaPoints($data['values'], $data['max']);
@endphp
<div class="chart-svg-wrap">
    <svg viewBox="0 0 {{ $chart['width'] }} {{ $chart['height'] }}" class="chart-svg" role="img" aria-label="Lead trend chart">
        @foreach ([0, 25, 50, 75, 100] as $tick)
            @php $y = $chart['padY'] + $chart['innerH'] - (($tick / 100) * $chart['innerH']); @endphp
            <line x1="{{ $chart['padX'] }}" y1="{{ $y }}" x2="{{ $chart['width'] - $chart['padX'] }}" y2="{{ $y }}" class="chart-grid" />
            <text x="8" y="{{ $y + 4 }}" class="chart-axis">{{ $tick }}</text>
        @endforeach
        <polygon points="{{ $chart['area'] }}" class="chart-area-fill" />
        <polyline points="{{ $chart['line'] }}" class="chart-area-line" fill="none" />
        @foreach ($data['labels'] as $i => $label)
            @php $x = $chart['points'][$i][0] ?? 0; @endphp
            <text x="{{ $x }}" y="{{ $chart['height'] - 2 }}" text-anchor="middle" class="chart-axis">{{ $label }}</text>
        @endforeach
    </svg>
</div>
