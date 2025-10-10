<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'scholarship_id',
        'granted_date',
        'valid_until',
        'remarks',
        'status',
    ];

    protected $casts = [
        'granted_date' => 'date',
        'valid_until' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
