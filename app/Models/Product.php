<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'sold',
        'category_id',
        'image',
    ];

    /**
     * Relasi: Produk milik satu kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}