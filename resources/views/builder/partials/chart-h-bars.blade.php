@php
    $labels = $data['labels'];
    $series = $data['series'];
    $max = max($data['max'], 1);
@endphp
<div class="chart-hbar-wrap">
    @foreach ($labels as $i => $label)
        <div class="chart-hbar-row">
            <div class="chart-hbar-label">{{ $label }}</div>
            <div class="chart-hbar-tracks">
                @foreach ($series as $serie)
                    <div class="chart-hbar-track">
                        <div class="chart-hbar-fill" style="width: {{ ($serie['values'][$i] / $max) * 100 }}%; background: {{ $serie['color'] }}"></div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="chart-hbar-x">
        @foreach ([0, 20, 40, 60, 80] as $tick)
            <span>{{ $tick }}</span>
        @endforeach
    </div>
</div>
@include('builder.partials.chart-legend', [
    'items' => collect($series)->map(fn ($s) => ['key' => $s['key'], 'color' => $s['color']])->all(),
])
