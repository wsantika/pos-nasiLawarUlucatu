<?php

namespace App\Services;

use App\Models\Transaction;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ThermalPrinterService
{
    private int $lineWidth;

    public function __construct()
    {
        $this->lineWidth = (int) config('thermal-printer.line_width', 32);
    }

    public function printCustomerReceipt(Transaction $transaction): void
    {
        if (!config('thermal-printer.enabled')) {
            return;
        }

        $transaction->loadMissing(['details.product', 'user']);

        $printer = $this->makePrinter();

        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("NASI LAWAR\n");
            $printer->text("ULUCATU\n");
            $printer->setEmphasis(false);
            $printer->text("Struk Pembayaran\n");
            $printer->feed();

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($this->separator());
            $printer->text($this->row('Invoice', $transaction->invoice_number));
            $printer->text($this->row('Tanggal', $transaction->created_at->format('d/m/Y H:i')));
            $printer->text($this->row('Kasir', $transaction->user->name ?? '-'));
            $printer->text($this->row('Customer', $transaction->customer_name ?: '-'));
            $printer->text($this->row('Order', $this->formatOrderType($transaction->order_type)));

            if ($transaction->order_type === 'dine-in') {
                $printer->text($this->row('Meja', $transaction->table_number ?? '-'));
            }

            $printer->text($this->separator());

            foreach ($transaction->details as $detail) {
                $printer->text($this->wrap($detail->product->name ?? 'Produk'));

                $qtyPrice = $detail->quantity . ' x ' . $this->money($detail->price);
                $subtotal = $this->money($detail->subtotal);

                $printer->text($this->row($qtyPrice, $subtotal));
            }

            $printer->text($this->separator());
            $printer->text($this->row('Subtotal', $this->money($transaction->subtotal)));
            $printer->text($this->row('Diskon', $this->money($transaction->discount)));
            $printer->text($this->row('Pajak', $this->money($transaction->tax)));

            $printer->setEmphasis(true);
            $printer->text($this->row('TOTAL', $this->money($transaction->total)));
            $printer->setEmphasis(false);

            $printer->text($this->row('Bayar', $this->money($transaction->paid)));
            $printer->text($this->row('Kembali', $this->money($transaction->change)));
            $printer->text($this->row('Metode', strtoupper($transaction->payment_method)));
            $printer->text($this->separator());

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih\n");
            $printer->text("Selamat menikmati\n");
            $printer->feed(3);

            if (config('thermal-printer.cut')) {
                $printer->cut();
            }
        } finally {
            $printer->close();
        }
    }

    public function printKitchenTicket(Transaction $transaction): void
    {
        if (!config('thermal-printer.enabled')) {
            return;
        }

        $transaction->loadMissing(['details.product', 'user']);

        $printer = $this->makePrinter();

        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("KITCHEN ORDER\n");
            $printer->text("TICKET\n");
            $printer->setEmphasis(false);
            $printer->feed();

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($this->separator());
            $printer->text($this->row('Invoice', $transaction->invoice_number));
            $printer->text($this->row('Waktu', $transaction->created_at->format('d/m/Y H:i')));
            $printer->text($this->row('Customer', $transaction->customer_name ?: '-'));
            $printer->text($this->row('Order', $this->formatOrderType($transaction->order_type)));

            if ($transaction->order_type === 'dine-in') {
                $printer->text($this->row('Meja', $transaction->table_number ?? '-'));
            }

            $printer->text($this->separator());

            foreach ($transaction->details as $detail) {
                $printer->setEmphasis(true);
                $printer->text($detail->quantity . 'x ');
                $printer->setEmphasis(false);
                $printer->text($this->wrap($detail->product->name ?? 'Produk'));
            }

            $printer->text($this->separator());
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Untuk dapur\n");
            $printer->feed(3);

            if (config('thermal-printer.cut')) {
                $printer->cut();
            }
        } finally {
            $printer->close();
        }
    }

    private function makePrinter(): Printer
    {
        $profile = CapabilityProfile::load('simple');
        $connector = new WindowsPrintConnector(config('thermal-printer.name'));

        return new Printer($connector, $profile);
    }

    private function separator(): string
    {
        return str_repeat('-', $this->lineWidth) . "\n";
    }

    private function row(string $left, string $right): string
    {
        $left = trim($left);
        $right = trim($right);

        $space = $this->lineWidth - strlen($left) - strlen($right);

        if ($space < 1) {
            $space = 1;
        }

        return $left . str_repeat(' ', $space) . $right . "\n";
    }

    private function wrap(string $text): string
    {
        return wordwrap($text, $this->lineWidth, "\n", true) . "\n";
    }

    private function money($amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    private function formatOrderType(?string $orderType): string
    {
        return $orderType === 'take-away' ? 'Take-away' : 'Dine-in';
    }
}
