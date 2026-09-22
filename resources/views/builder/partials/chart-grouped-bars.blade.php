@php
    $labels = $data['labels'];
    $series = $data['series'];
    $max = max($data['max'], 1);
    $groupCount = count($labels);
    $seriesCount = count($series);
@endphp
<div class="chart-bars-wrap">
    <div class="chart-bars-y">
        @foreach ([100, 75, 50, 25, 0] as $tick)
            <span>{{ $tick }}</span>
        @endforeach
    </div>
    <div class="chart-bars">
        @foreach ($labels as $i => $label)
            <div class="chart-bar-group">
                <div class="chart-bar-cols">
                    @foreach ($series as $serie)
                        <div class="chart-bar" style="height: {{ ($serie['values'][$i] / $max) * 100 }}%; background: {{ $serie['color'] }}" title="{{ $serie['key'] }}: {{ $serie['values'][$i] }}"></div>
                    @endforeach
                </div>
                <span class="chart-bar-label">{{ $label }}</span>
            </div>
        @endforeach
    </div>
</div>
@include('builder.partials.chart-legend', [
    'items' => collect($series)->map(fn ($s) => ['key' => $s['key'], 'color' => $s['color']])->all(),
])
