<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'complaint_number',
        'user_id',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'attachment',
        'assigned_to',
        'admin_response',
        'resolved_at',
        'resolved_by',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->complaint_number) {
                $model->complaint_number = 'CMP-' . date('Ymd') . '-' . str_pad(Complaint::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedToUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function markAsResolved($adminResponse, $userId)
    {
        $this->update([
            'status' => 'resolved',
            'admin_response' => $adminResponse,
            'resolved_at' => now(),
            'resolved_by' => $userId,
        ]);
    }

    public function isOpen()
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    public function isResolved()
    {
        return in_array($this->status, ['resolved', 'closed']);
    }
}
