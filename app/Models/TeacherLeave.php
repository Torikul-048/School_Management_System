<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLeave extends Model
{
    protected $fillable = [
        'teacher_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'supporting_document',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'total_days' => 'integer',
    ];

    /**
     * Get the teacher for this leave
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the leave type
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Get the approver user
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for pending leaves
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved leaves
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected leaves
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
