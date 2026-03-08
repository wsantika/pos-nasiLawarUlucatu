<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    // Relasi: 1 Transaksi dilayani 1 Kasir (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 Transaksi punya banyak detail menu yang dipesan
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}