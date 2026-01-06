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

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }
    
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount && $this->discount->isActive()) {
            return $this->price * (1 - $this->discount->percentage / 100);
        }
        return $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->discount && $this->discount->isActive()) {
            return $this->discount->percentage;
        }
        return 0;
    }
}