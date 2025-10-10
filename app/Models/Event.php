<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'organizer',
        'target_audience',
        'image',
        'is_public',
        'send_notification',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_audience' => 'array',
        'is_public' => 'boolean',
        'send_notification' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'published')
            ->where('start_date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('status', 'published')
            ->where('end_date', '<', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'published')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function isUpcoming()
    {
        return $this->start_date >= now();
    }

    public function isOngoing()
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    public function isPast()
    {
        return $this->end_date < now();
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
