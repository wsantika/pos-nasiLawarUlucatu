<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $guarded = ['id'];

    // 1 Transaksi dilayani 1 Kasir
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 1 Transaksi punya banyak detail
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}