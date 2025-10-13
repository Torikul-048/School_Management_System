<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admission_number',
        'roll_number',
        'class_id',
        'section_id',
        'academic_year_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'religion',
        'caste',
        'category',
        'mother_tongue',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'nationality',
        'current_address',
        'permanent_address',
        'father_name',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_occupation',
        'parent_user_id',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
        'guardian_email',
        'guardian_occupation',
        'guardian_address',
        'previous_school',
        'admission_date',
        'photo',
        'birth_certificate',
        'transfer_certificate',
        'documents',
        'medical_history',
        'allergies',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    /**
     * Get the user associated with the student
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the class that the student belongs to
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get the section that the student belongs to
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the academic year
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get all attendance records
     */
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    /**
     * Get all grades
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get all fee invoices
     */
    public function feeInvoices(): HasMany
    {
        return $this->hasMany(FeeInvoice::class);
    }

    /**
     * Get all book issues
     */
    public function bookIssues(): HasMany
    {
        return $this->hasMany(BookIssue::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope for active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive students
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for filtering by class
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope for filtering by section
     */
    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Scope for filtering by academic year
     */
    public function scopeByAcademicYear($query, $yearId)
    {
        return $query->where('academic_year_id', $yearId);
    }

    /**
     * Scope for searching students
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('admission_number', 'like', "%{$search}%")
              ->orWhere('roll_number', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
