<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionPaymentService
{
    public function markAsSuccess(Transaction $transaction): Transaction
    {
        return DB::transaction(function () use ($transaction) {
            $transaction = Transaction::with('details.product')
                ->whereKey($transaction->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->payment_status === 'success') {
                return $transaction;
            }

            if ($transaction->payment_status !== 'pending') {
                throw new \Exception('Transaksi tidak bisa dikonfirmasi karena status pembayaran bukan pending.');
            }

            foreach ($transaction->details as $detail) {
                $product = Product::active()
                    ->whereKey($detail->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception('Produk "' . ($detail->product->name ?? 'Tidak diketahui') . '" sudah dinonaktifkan.');
                }

                if ($product->stock < $detail->quantity) {
                    throw new \Exception('Stok produk "' . $product->name . '" tidak mencukupi.');
                }

                $product->decrement('stock', $detail->quantity);
            }

            $transaction->update([
                'paid' => $transaction->total,
                'change' => 0,
                'payment_status' => 'success',
            ]);

            return $transaction->fresh(['details.product', 'user']);
        });
    }

    public function markAsFailed(Transaction $transaction): void
    {
        if ($transaction->payment_status !== 'pending') {
            throw new \Exception('Hanya transaksi pending yang bisa dibatalkan.');
        }

        $transaction->update(['payment_status' => 'failed']);
    }
}
