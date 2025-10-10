<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_number',
        'book_id',
        'student_id',
        'teacher_id',
        'issue_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
        'fine_paid',
        'remarks',
        'issued_by',
        'returned_to',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
        'fine_paid' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->issue_number) {
                $model->issue_number = 'ISS-' . date('Ymd') . '-' . str_pad(BookIssue::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    public function getBorrowerAttribute()
    {
        return $this->student ?? $this->teacher;
    }

    public function getBorrowerTypeAttribute()
    {
        return $this->student_id ? 'Student' : 'Teacher';
    }

    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'issued')
            ->where('due_date', '<', now());
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function isOverdue()
    {
        return $this->status === 'issued' && $this->due_date < now();
    }

    public function calculateFine()
    {
        if ($this->status !== 'issued' || $this->due_date >= now()) {
            return 0;
        }

        $settings = \DB::table('library_settings')->first();
        $finePerDay = $settings->fine_per_day ?? 5.00;
        
        $daysLate = now()->diffInDays($this->due_date);
        return $daysLate * $finePerDay;
    }

    public function getDaysLateAttribute()
    {
        if ($this->status !== 'issued' || $this->due_date >= now()) {
            return 0;
        }
        return now()->diffInDays($this->due_date);
    }
}
