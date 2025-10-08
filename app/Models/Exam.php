<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year_id',
        'exam_type',
        'start_date',
        'end_date',
        'description',
        'status',
        'results_published',
        'published_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'results_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the academic year
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get exam schedules
     */
    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    /**
     * Get marks
     */
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * Scope for upcoming exams
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('start_date', '>', now());
    }

    /**
     * Scope for ongoing exams
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Scope for completed exams
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
