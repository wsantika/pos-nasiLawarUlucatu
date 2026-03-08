<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;
use App\Models\Transaction;

class TransactionDetail extends Model
{
    protected $guarded = ['id'];

    // Detail milik 1 transaksi
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // Detail merujuk ke 1 produk
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}