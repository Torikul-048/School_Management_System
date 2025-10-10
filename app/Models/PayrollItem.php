<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_id',
        'item_type',
        'item_name',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the payroll for this item
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
