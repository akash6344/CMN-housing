<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMedia extends Model
{
    use HasFactory;

    protected $table = 'project_media';

    protected $fillable = [
        'project_detail_id',
        'category',
        'title',
        'file_name',
        'file_path',
        'file_url',
        'mime_type',
        'file_size',
        'is_cover',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'is_cover'   => 'boolean',
        'file_size'  => 'integer',
        'sort_order' => 'integer',
        'metadata'   => 'array',
    ];

    /**
     * Project this media belongs to.
     */
    public function projectDetail(): BelongsTo
    {
        return $this->belongsTo(ProjectDetail::class);
    }

    /**
     * Scope: only images / photos.
     */
    public function scopePhotos($query)
    {
        return $query->whereIn('category', ['elevation', 'amenities', 'sample_flat', 'site_photo', 'gallery', 'photos', 'general']);
    }

    /**
     * Scope: only floor plans.
     */
    public function scopeFloorPlans($query)
    {
        return $query->whereIn('category', ['floor_plan_2d', 'floor_plan_3d', 'floor_plans']);
    }

    /**
     * Scope: only cover photo.
     */
    public function scopeCover($query)
    {
        return $query->where('is_cover', true);
    }
}
