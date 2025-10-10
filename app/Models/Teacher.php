<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'blood_group',
        'nationality',
        'religion',
        'marital_status',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'current_address',
        'permanent_address',
        'emergency_contact',
        'emergency_contact_name',
        'emergency_contact_phone',
        'qualification',
        'specialization',
        'experience_years',
        'joining_date',
        'department',
        'designation',
        'salary',
        'employment_type',
        'bank_name',
        'bank_account',
        'bank_account_number',
        'bank_ifsc_code',
        'tax_id',
        'photo',
        'resume',
        'certificates',
        'documents',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'experience_years' => 'integer',
        'salary' => 'decimal:2',
        'certificates' => 'array',
        'documents' => 'array',
    ];

    /**
     * Get the user that owns the teacher
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subjects assigned to the teacher
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id');
    }

    /**
     * Get the classes taught by the teacher
     */
    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'subject_assignments', 'teacher_id', 'class_id')
            ->distinct();
    }

    /**
     * Get all subject assignments for the teacher
     */
    public function subjectAssignments()
    {
        return $this->hasMany(SubjectAssignment::class);
    }

    /**
     * Get attendances for the teacher
     */
    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    /**
     * Get leave requests for the teacher
     */
    public function leaveRequests()
    {
        return $this->morphMany(LeaveRequest::class, 'leaveable');
    }

    /**
     * Get salary structure for the teacher
     */
    public function salaryStructure()
    {
        return $this->hasOne(SalaryStructure::class);
    }

    /**
     * Get payroll records for the teacher
     */
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Get teacher leave requests
     */
    public function teacherLeaves()
    {
        return $this->hasMany(TeacherLeave::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get age attribute
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Check if teacher is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Scope to get only active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only inactive teachers
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get all book issues
     */
    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }
}
