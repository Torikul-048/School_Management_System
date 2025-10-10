<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'notice_type',
        'priority',
        'target_audience',
        'publish_date',
        'expiry_date',
        'attachment',
        'send_email',
        'send_sms',
        'is_pinned',
        'status',
        'created_by',
    ];

    protected $casts = [
        'target_audience' => 'array',
        'publish_date' => 'date',
        'expiry_date' => 'date',
        'send_email' => 'boolean',
        'send_sms' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('publish_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            });
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('notice_type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function isActive()
    {
        return $this->status === 'published' 
            && $this->publish_date <= now() 
            && (!$this->expiry_date || $this->expiry_date >= now());
    }
}
