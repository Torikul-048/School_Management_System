<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibrarySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'max_books_per_student',
        'max_books_per_teacher',
        'student_issue_days',
        'teacher_issue_days',
        'fine_per_day',
        'max_renewal_times',
    ];

    protected $casts = [
        'fine_per_day' => 'decimal:2',
    ];

    public static function getSettings()
    {
        return static::first() ?? static::create([
            'max_books_per_student' => 3,
            'max_books_per_teacher' => 5,
            'student_issue_days' => 14,
            'teacher_issue_days' => 30,
            'fine_per_day' => 5.00,
            'max_renewal_times' => 2,
        ]);
    }
}
