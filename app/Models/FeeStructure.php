<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'fee_type',
        'frequency',
        'class_id',
        'applicable_from',
        'applicable_to',
        'is_mandatory',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'applicable_from' => 'date',
        'applicable_to' => 'date',
        'is_mandatory' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId)->orWhereNull('class_id');
    }
}
