<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'account_number',
        'gateway_config',
        'transaction_charge',
        'status',
    ];

    protected $casts = [
        'gateway_config' => 'array',
        'transaction_charge' => 'decimal:2',
    ];

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isOnlineGateway()
    {
        return in_array($this->code, ['bkash', 'nagad', 'card', 'online']);
    }
}
