<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment)
     */
    protected $fillable = [
        'name',   // ← TAMBAHKAN INI
    ];

    /**
     * Relasi: satu kategori punya banyak produk
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}