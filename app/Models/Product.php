<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id', // Ini yang menyebabkan error tadi
    ];

    /**
     * Relasi ke Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
