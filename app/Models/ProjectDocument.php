<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDocument extends Model
{
    use HasFactory;

    protected $table = 'project_documents';

    protected $fillable = [
        'project_detail_id',
        'category',
        'title',
        'document_number',
        'file_name',
        'file_path',
        'file_url',
        'mime_type',
        'file_size',
        'status',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'file_size'  => 'integer',
        'metadata'   => 'array',
    ];

    /**
     * Project this document belongs to.
     */
    public function projectDetail(): BelongsTo
    {
        return $this->belongsTo(ProjectDetail::class);
    }

    /**
     * Scope: only verified documents.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope: only pending documents.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: filter by category (rera, brochure, price_list, approval, agreement).
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
