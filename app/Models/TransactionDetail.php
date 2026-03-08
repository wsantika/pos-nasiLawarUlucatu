<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $guarded = ['id'];

    // Relasi: Detail ini milik 1 Transaksi
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi: Detail ini merujuk ke 1 Produk/Menu
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}