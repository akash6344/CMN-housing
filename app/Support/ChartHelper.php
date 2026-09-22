<?php

namespace App\Support;

class ChartHelper
{
    public static function areaPoints(array $values, int $max, int $width = 520, int $height = 180, int $padX = 36, int $padY = 16): array
    {
        $count = max(count($values), 1);
        $span = max($count - 1, 1);
        $innerW = $width - ($padX * 2);
        $innerH = $height - ($padY * 2);
        $points = [];

        foreach ($values as $i => $value) {
            $x = $padX + ($innerW * ($i / $span));
            $y = $padY + $innerH - (($value / max($max, 1)) * $innerH);
            $points[] = [round($x, 1), round($y, 1)];
        }

        $line = collect($points)->map(fn ($p) => $p[0].','.$p[1])->implode(' ');
        $first = $points[0];
        $last = $points[array_key_last($points)];
        $area = $line.' '.$last[0].','.($height - $padY).' '.$first[0].','.($height - $padY);

        return compact('line', 'area', 'points', 'width', 'height', 'padX', 'padY', 'innerW', 'innerH');
    }

    public static function donutSegments(array $items, float $radius = 54, float $stroke = 18): array
    {
        $total = max(array_sum(array_column($items, 'value')), 1);
        $circumference = 2 * M_PI * $radius;
        $offset = 0;
        $segments = [];

        foreach ($items as $item) {
            $length = ($item['value'] / $total) * $circumference;
            $segments[] = [
                'label' => $item['label'],
                'color' => $item['color'],
                'dash' => round($length, 2).' '.round($circumference - $length, 2),
                'offset' => round(-$offset, 2),
            ];
            $offset += $length;
        }

        return [
            'segments' => $segments,
            'radius' => $radius,
            'stroke' => $stroke,
            'circumference' => $circumference,
        ];
    }
}
