<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'event_date',
        'images',
        'cover_image',
        'is_featured',
        'is_public',
        'status',
        'created_by',
        'views_count',
    ];

    protected $casts = [
        'event_date' => 'date',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_public' => 'boolean',
        'views_count' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('event_date', 'desc');
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function getImageCountAttribute()
    {
        return $this->images ? count($this->images) : 0;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
