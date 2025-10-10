<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'class_id',
        'section_id',
        'subject_id',
        'academic_year_id',
        'periods_per_week',
        'room_number',
        'day_of_week',
        'time_slot',
    ];

    protected $casts = [
        'periods_per_week' => 'integer',
    ];

    /**
     * Get the teacher for this assignment
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the class for this assignment
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get the section for this assignment
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject for this assignment
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the academic year for this assignment
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
