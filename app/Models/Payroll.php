<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'teacher_id',
        'month',
        'payment_date',
        'basic_salary',
        'allowances',
        'deductions',
        'gross_salary',
        'net_salary',
        'payment_method',
        'status',
        'remarks',
        'working_days',
        'present_days',
        'absent_days',
        'attendance_deduction',
    ];

    protected $casts = [
        'month' => 'string',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'attendance_deduction' => 'decimal:2',
        'working_days' => 'integer',
        'present_days' => 'integer',
        'absent_days' => 'integer',
    ];

    /**
     * Get the teacher for this payroll
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the payroll items
     */
    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }

    /**
     * Scope for pending payrolls
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid payrolls
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
