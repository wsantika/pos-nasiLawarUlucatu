<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: 1 Produk dimiliki oleh 1 Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scope: hanya produk yang aktif (untuk kasir)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}