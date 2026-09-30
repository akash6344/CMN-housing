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

        // Additional Metadata / Workflow
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
     * Normalized Media (photos, elevation, floor plans, video, virtual tours).
     */
    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Photos only (elevation, amenities, gallery).
     */
    public function photos(): HasMany
    {
        return $this->media()->whereIn('category', ['elevation', 'amenities', 'sample_flat', 'site_photo', 'gallery', 'photos', 'general']);
    }

    /**
     * Floor plans only (2D and 3D).
     */
    public function floorPlans(): HasMany
    {
        return $this->media()->whereIn('category', ['floor_plan_2d', 'floor_plan_3d', 'floor_plans']);
    }

    /**
     * Normalized Documents (brochures, price lists, RERA certificates, approvals).
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
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
     * Scope to return projects under review.
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope to filter by project type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('project_type', $type);
    }

    /**
     * Mark project as submitted for review.
     */
    public function submitForReview(): static
    {
        $this->status = 'under_review';
        $this->save();
        return $this;
    }

    /**
     * Publish the project.
     */
    public function publish(): static
    {
        $this->status = 'published';
        $this->save();
        return $this;
    }

    public function getIsUnderReviewAttribute(): bool
    {
        return $this->status === 'under_review';
    }

    public function getIsDraftAttribute(): bool
    {
        return $this->status === 'draft';
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Dynamic video URL from normalized media table.
     */
    public function getVideoUrlAttribute(): ?string
    {
        return $this->media()->where('category', 'video')->value('file_url');
    }

    /**
     * Dynamic virtual tour URL from normalized media table.
     */
    public function getVirtualTourUrlAttribute(): ?string
    {
        return $this->media()->where('category', 'virtual_tour')->value('file_url');
    }

    /**
     * Returns cover photo URL from normalized media.
     */
    public function getCoverPhotoAttribute(): ?string
    {
        return $this->media()->where('is_cover', true)->value('file_url')
            ?? $this->photos()->first()?->file_url;
    }

    /**
     * Total available units across all configurations.
     */
    public function getTotalAvailableUnitsCountAttribute(): int
    {
        return (int) $this->unitConfigurations()->sum('available_units');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'under_review' => 'Pending Review',
            'published'    => 'Active',
            'archived'     => 'Archived',
            default        => 'Draft',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'under_review' => 'chip-hold',
            'published'    => 'chip-live',
            'archived'     => 'chip-rejected',
            default        => 'chip-counter',
        };
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
