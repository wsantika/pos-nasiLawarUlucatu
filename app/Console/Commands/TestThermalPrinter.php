<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class TestThermalPrinter extends Command
{
    protected $signature = 'printer:test';
    protected $description = 'Test print ke printer thermal';

    public function handle(): int
    {
        $printerName = config('thermal-printer.name');

        $this->info('Mencoba print ke printer: ' . $printerName);

        $profile = CapabilityProfile::load('simple');
        $connector = new WindowsPrintConnector($printerName);
        $printer = new Printer($connector, $profile);

        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("NASI LAWAR ULUCATU\n");
            $printer->setEmphasis(false);
            $printer->text("TEST PRINT\n");
            $printer->feed();

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("--------------------------------\n");
            $printer->text("Printer: {$printerName}\n");
            $printer->text("Waktu  : " . now()->format('d/m/Y H:i:s') . "\n");
            $printer->text("--------------------------------\n");
            $printer->feed(3);

            if (config('thermal-printer.cut')) {
                $printer->cut();
            }
        } finally {
            $printer->close();
        }

        $this->info('Test print berhasil dikirim.');
        return self::SUCCESS;
    }
}