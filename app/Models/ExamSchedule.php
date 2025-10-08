<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'class_id',
        'section_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'total_marks',
        'passing_marks',
        'room_number',
        'instructions',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
    ];

    /**
     * Get the exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the class
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get the section
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get marks for this schedule
     */
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
