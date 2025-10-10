<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'discount_type',
        'discount_value',
        'max_students',
        'min_percentage',
        'valid_from',
        'valid_to',
        'applicable_fee_types',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_percentage' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'applicable_fee_types' => 'array',
    ];

    public function studentScholarships()
    {
        return $this->hasMany(StudentScholarship::class);
    }

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function calculateDiscount($amount)
    {
        if ($this->discount_type === 'percentage') {
            return ($amount * $this->discount_value) / 100;
        }
        return $this->discount_value;
    }
}
