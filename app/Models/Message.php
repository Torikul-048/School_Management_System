<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
        'attachment',
        'is_read',
        'read_at',
        'priority',
        'status',
        'parent_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeInbox($query, $userId)
    {
        return $query->where('receiver_id', $userId)
            ->where('status', 'sent');
    }

    public function scopeOutbox($query, $userId)
    {
        return $query->where('sender_id', $userId)
            ->where('status', 'sent');
    }

    public function scopeArchived($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        })->where('status', 'archived');
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
