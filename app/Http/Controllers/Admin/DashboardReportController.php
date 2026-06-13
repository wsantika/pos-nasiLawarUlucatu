<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $parsedDate = Carbon::parse($date);

        $transactions = Transaction::whereDate('created_at', $parsedDate)
            ->where('payment_status', 'success')
            ->get();

        $totalOmzet      = $transactions->sum('total');
        $jumlahTransaksi = $transactions->count();
        $rataRata        = $jumlahTransaksi > 0 ? $totalOmzet / $jumlahTransaksi : 0;

        // Ambil detail menu dari transaction_details
        $menuSales = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereDate('transactions.created_at', $parsedDate)
            ->where('transactions.payment_status', 'success')
            ->select('products.name', DB::raw('SUM(transaction_details.quantity) as total_qty'))
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->get();

        $totalPorsi  = $menuSales->sum('total_qty');
        $stokMenipis = Product::where('stock', '<=', 10)->get();

        $data = [
            'jenis'           => 'Harian',
            'periode'         => $parsedDate->translatedFormat('d F Y'),
            'totalOmzet'      => $totalOmzet,
            'jumlahTransaksi' => $jumlahTransaksi,
            'rataRata'        => $rataRata,
            'totalPorsi'      => $totalPorsi,
            'menuSales'       => $menuSales,
            'stokMenipis'     => $stokMenipis,
            'tanggalCetak'    => Carbon::now()->translatedFormat('d F Y, H:i'),
        ];

        $pdf = Pdf::loadView('pdf.dashboard-report', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-harian-' . $date . '.pdf');
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', Carbon::today()->month);
        $year  = $request->get('year', Carbon::today()->year);

        $transactions = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('payment_status', 'success')
            ->get();

        $totalOmzet      = $transactions->sum('total');
        $jumlahTransaksi = $transactions->count();
        $rataRata        = $jumlahTransaksi > 0 ? $totalOmzet / $jumlahTransaksi : 0;

        $menuSales = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereMonth('transactions.created_at', $month)
            ->whereYear('transactions.created_at', $year)
            ->where('transactions.payment_status', 'success')
            ->select('products.name', DB::raw('SUM(transaction_details.quantity) as total_qty'))
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->get();

        $totalPorsi  = $menuSales->sum('total_qty');
        $stokMenipis = Product::where('stock', '<=', 10)->get();
        $namaBulan   = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        $data = [
            'jenis'           => 'Bulanan',
            'periode'         => $namaBulan,
            'totalOmzet'      => $totalOmzet,
            'jumlahTransaksi' => $jumlahTransaksi,
            'rataRata'        => $rataRata,
            'totalPorsi'      => $totalPorsi,
            'menuSales'       => $menuSales,
            'stokMenipis'     => $stokMenipis,
            'tanggalCetak'    => Carbon::now()->translatedFormat('d F Y, H:i'),
        ];

        $pdf = Pdf::loadView('pdf.dashboard-report', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-bulanan-' . $year . '-' . $month . '.pdf');
    }
}