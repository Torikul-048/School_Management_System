<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'author',
        'publisher',
        'publication_year',
        'category_id',
        'description',
        'language',
        'total_copies',
        'available_copies',
        'price',
        'rack_location',
        'cover_image',
        'pdf_file',
        'barcode',
        'qr_code',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'publication_year' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->barcode) {
                $model->barcode = 'BK-' . strtoupper(uniqid());
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function currentIssues()
    {
        return $this->hasMany(BookIssue::class)->where('status', 'issued');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('available_copies', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAuthor($query, $author)
    {
        return $query->where('author', 'like', '%' . $author . '%');
    }

    public function isAvailable()
    {
        return $this->status === 'available' && $this->available_copies > 0;
    }

    public function hasDigitalCopy()
    {
        return !empty($this->pdf_file);
    }
}
