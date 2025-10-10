<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    protected $fillable = [
        'teacher_id',
        'basic_salary',
        'hra',
        'transport_allowance',
        'medical_allowance',
        'special_allowance',
        'other_allowance',
        'total_allowances',
        'provident_fund',
        'professional_tax',
        'income_tax',
        'other_deductions',
        'total_deductions',
        'gross_salary',
        'net_salary',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'special_allowance' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'provident_fund' => 'decimal:2',
        'professional_tax' => 'decimal:2',
        'income_tax' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    /**
     * Get the teacher for this salary structure
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
