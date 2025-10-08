<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'exam_schedule_id',
        'student_id',
        'subject_id',
        'marks_obtained',
        'total_marks',
        'grade',
        'grade_point',
        'remarks',
        'is_absent',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'total_marks' => 'integer',
        'grade_point' => 'decimal:2',
        'is_absent' => 'boolean',
    ];

    /**
     * Get the exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the exam schedule
     */
    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Calculate percentage
     */
    public function getPercentageAttribute()
    {
        if ($this->total_marks == 0 || $this->is_absent) {
            return 0;
        }
        
        return round(($this->marks_obtained / $this->total_marks) * 100, 2);
    }

    /**
     * Check if passed
     */
    public function getIsPassedAttribute()
    {
        if ($this->is_absent) {
            return false;
        }

        $passingMarks = $this->examSchedule->passing_marks ?? ($this->total_marks * 0.33);
        return $this->marks_obtained >= $passingMarks;
    }
}
