<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'widget_key',
        'widget_type',
        'chart_type',
        'icon',
        'color',
        'roles',
        'data_source',
        'configuration',
        'is_active',
        'sort_order',
        'refresh_interval',
    ];

    protected $casts = [
        'roles' => 'array',
        'configuration' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForRole($query, $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereJsonContains('roles', $role)
                ->orWhereNull('roles');
        });
    }
}
