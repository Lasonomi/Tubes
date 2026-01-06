<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Discount extends Model
{
    protected $fillable = ['product_id', 'percentage', 'start_date', 'end_date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scope untuk diskon aktif
    public function scopeActive(Builder $query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $now);
        });
    }

    // Method instance untuk cek satu diskon aktif (sudah ada sebelumnya)
    public function isActive()
    {
        $now = now();
        return (!$this->start_date || $this->start_date <= $now) &&
               (!$this->end_date || $this->end_date >= $now);
    }
}