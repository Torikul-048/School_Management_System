<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'student_id',
        'fee_structure_id',
        'payment_method_id',
        'fee_amount',
        'discount_amount',
        'fine_amount',
        'paid_amount',
        'payment_date',
        'due_date',
        'month',
        'year',
        'transaction_id',
        'cheque_number',
        'cheque_date',
        'bank_name',
        'scholarship_id',
        'remarks',
        'status',
        'collected_by',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'date',
        'due_date' => 'date',
        'cheque_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->receipt_number) {
                $model->receipt_number = 'RCP-' . date('Ymd') . '-' . str_pad(FeeCollection::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }
}
