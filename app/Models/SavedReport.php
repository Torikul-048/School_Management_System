<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_template_id',
        'user_id',
        'report_name',
        'parameters',
        'filters',
        'file_path',
        'format',
        'generated_at',
        'download_count',
    ];

    protected $casts = [
        'parameters' => 'array',
        'filters' => 'array',
        'generated_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementDownloads()
    {
        $this->increment('download_count');
    }
}
