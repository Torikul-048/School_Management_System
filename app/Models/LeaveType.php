<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'max_days_per_year',
        'is_paid',
        'applicable_to',
        'requires_document',
        'status',
    ];

    protected $casts = [
        'max_days_per_year' => 'integer',
        'is_paid' => 'boolean',
        'requires_document' => 'boolean',
    ];

    /**
     * Get teacher leaves for this leave type
     */
    public function teacherLeaves()
    {
        return $this->hasMany(TeacherLeave::class);
    }

    /**
     * Get student leaves for this leave type (if applicable)
     */
    public function studentLeaves()
    {
        return $this->hasMany(LeaveRequest::class, 'leave_type');
    }

    /**
     * Scope for active leave types
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
