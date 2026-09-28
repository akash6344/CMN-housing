<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',

        // Step 1: Basic Details
        'name',
        'tagline',
        'builder_name',
        'location',
        'maps_link',
        'project_type',
        'project_status',
        'possession_date',
        'rera_number',
        'towers',
        'total_units',
        'land_area',

        // Step 2: Smart Bargain Settings
        'smart_bargain_enabled',
        'min_expected_price',
        'target_price',
        'max_price',

        // Step 3: Amenities
        'amenities',

        // Additional
        'description',
        'highlights',
        'status',
    ];

    protected $casts = [
        'smart_bargain_enabled' => 'boolean',
        'possession_date'       => 'date',
        'min_expected_price'    => 'decimal:2',
        'target_price'          => 'decimal:2',
        'max_price'             => 'decimal:2',
        'amenities'             => 'array',
        'highlights'            => 'array',
        'towers'                => 'integer',
        'total_units'           => 'integer',
    ];

    /**
     * The builder/owner of this project (optional user account).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All unit configurations (BHKs, areas, pricing) for this project.
     */
    public function unitConfigurations(): HasMany
    {
        return $this->hasMany(UnitConfiguration::class);
    }

    /**
     * Scope to only return published projects.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to only return draft projects.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to filter by project type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('project_type', $type);
    }

    /**
     * Returns a human-readable label for the starting price
     * based on the cheapest unit configuration.
     */
    public function getStartingPriceLabelAttribute(): string
    {
        $min = $this->unitConfigurations()->min('price');

        if (! $min) {
            return 'Price on Request';
        }

        if ($min >= 10_000_000) {
            return '₹ ' . number_format($min / 10_000_000, 2) . ' Cr*';
        }

        if ($min >= 100_000) {
            return '₹ ' . number_format($min / 100_000, 2) . ' L*';
        }

        return '₹ ' . number_format($min);
    }
}
