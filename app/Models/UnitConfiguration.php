<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_detail_id',
        'unit_type',
        'built_up_area',
        'carpet_area',
        'price',
        'price_per_sqft',
        'available_units',
        'floor_range',
        'show_floor_plan',
        'floor_plan_2d_url',
        'floor_plan_3d_url',
        'room_configurations',
    ];

    protected $casts = [
        'built_up_area'       => 'decimal:2',
        'carpet_area'         => 'decimal:2',
        'price'               => 'decimal:2',
        'price_per_sqft'      => 'decimal:2',
        'available_units'     => 'integer',
        'show_floor_plan'     => 'boolean',
        'room_configurations' => 'array',
    ];

    /**
     * The project this unit configuration belongs to.
     */
    public function projectDetail(): BelongsTo
    {
        return $this->belongsTo(ProjectDetail::class);
    }

    /**
     * Returns a human-readable price label (e.g. "₹ 1.13 Cr").
     */
    public function getPriceLabelAttribute(): string
    {
        $price = (float) $this->price;

        if ($price >= 10_000_000) {
            return '₹ ' . number_format($price / 10_000_000, 2) . ' Cr';
        }

        if ($price >= 100_000) {
            return '₹ ' . number_format($price / 100_000, 2) . ' L';
        }

        return '₹ ' . number_format($price);
    }

    /**
     * Recalculates and stores price_per_sqft from price ÷ built_up_area.
     * Call this before save() when price or built_up_area change.
     */
    public function recalculatePricePerSqft(): static
    {
        if ($this->built_up_area > 0) {
            $this->price_per_sqft = round((float) $this->price / (float) $this->built_up_area, 2);
        }

        return $this;
    }

    /**
     * Scope: only units with availability remaining.
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_units', '>', 0);
    }

    /**
     * Scope: filter by unit type label (case-insensitive partial match).
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('unit_type', 'like', "%{$type}%");
    }
}
