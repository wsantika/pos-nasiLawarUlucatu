<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard & Laporan - POS Admin Panel')]
class Dashboard extends Component
{
    private const LOW_STOCK_LIMIT = 10;

    public ?string $dailyDateInput = null;
    public ?string $monthInput = null;

    public ?string $dailyDate = null;
    public ?string $monthPeriod = null;

    public function mount(): void
    {
        $this->dailyDate = today()->toDateString();
        $this->dailyDateInput = $this->dailyDate;

        $this->monthPeriod = now()->format('Y-m');
        $this->monthInput = $this->monthPeriod;
    }

    public function applyReportFilter(): void
    {
        $this->validate([
            'dailyDateInput' => ['nullable', 'date'],
            'monthInput' => ['nullable', 'date_format:Y-m'],
        ], [
            'dailyDateInput.date' => 'Tanggal laporan harian tidak valid.',
            'monthInput.date_format' => 'Bulan laporan tidak valid.',
        ]);

        $this->dailyDate = $this->dailyDateInput ?: today()->toDateString();
        $this->monthPeriod = $this->monthInput ?: now()->format('Y-m');
    }

    public function resetReportFilter(): void
    {
        $this->resetValidation();

        $this->dailyDate = today()->toDateString();
        $this->dailyDateInput = $this->dailyDate;

        $this->monthPeriod = now()->format('Y-m');
        $this->monthInput = $this->monthPeriod;
    }

    private function getDashboardData(): array
    {
        $dailyDate = $this->dailyDate ?: today()->toDateString();
        $monthPeriod = $this->monthPeriod ?: now()->format('Y-m');
        $limit = self::LOW_STOCK_LIMIT;

        [$selectedYear, $selectedMonth] = array_map('intval', explode('-', $monthPeriod));

        $dailyTransactionQuery = Transaction::query()
            ->whereDate('created_at', $dailyDate)
            ->where('payment_status', 'success');

        $monthlyTransactionQuery = Transaction::query()
            ->whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth)
            ->where('payment_status', 'success');

        $dailyTransactionCount = (clone $dailyTransactionQuery)->count();
        $dailyRevenue = (clone $dailyTransactionQuery)->sum('total');

        $monthlyTransactionCount = (clone $monthlyTransactionQuery)->count();
        $monthlyRevenue = (clone $monthlyTransactionQuery)->sum('total');

        $dailyAverageTransaction = $dailyTransactionCount > 0
            ? (int) floor($dailyRevenue / $dailyTransactionCount)
            : 0;

        $monthlyAverageTransaction = $monthlyTransactionCount > 0
            ? (int) floor($monthlyRevenue / $monthlyTransactionCount)
            : 0;

        $dailySoldPortions = TransactionDetail::query()
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereDate('transactions.created_at', $dailyDate)
            ->where('transactions.payment_status', 'success')
            ->sum('transaction_details.quantity');

        $monthlySoldPortions = TransactionDetail::query()
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereYear('transactions.created_at', $selectedYear)
            ->whereMonth('transactions.created_at', $selectedMonth)
            ->where('transactions.payment_status', 'success')
            ->sum('transaction_details.quantity');

        $remainingPortions = Product::active()->sum('stock');

        $lowStockProductsCount = Product::active()
            ->where('stock', '<=', $limit)
            ->count();

        $stockProducts = Product::with('category')
            ->active()
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();

        $lowStockProducts = Product::with('category')
            ->active()
            ->where('stock', '<=', $limit)
            ->orderBy('stock', 'asc')
            ->get();

        $recentTransactions = Transaction::with('user')
            ->whereDate('created_at', $dailyDate)
            ->where('payment_status', 'success')
            ->latest()
            ->take(5)
            ->get();

        $topProducts = TransactionDetail::query()
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereDate('transactions.created_at', $dailyDate)
            ->where('transactions.payment_status', 'success')
            ->select(
                'products.id',
                'products.name',
                'categories.name as category_name',
                DB::raw('SUM(transaction_details.quantity) as total_sold'),
                DB::raw('SUM(transaction_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderByDesc('total_sold')
            ->get();

        $totalSold = (int) $topProducts->sum('total_sold');
        $runningSold = 0;

        $topProducts = $topProducts->values()->map(function ($product, $index) use ($totalSold, &$runningSold) {
            $runningSold += (int) $product->total_sold;

            $product->rank = $index + 1;
            $product->percentage = $totalSold > 0
                ? round(((int) $product->total_sold / $totalSold) * 100, 1)
                : 0;

            $product->cumulative_percentage = $totalSold > 0
                ? round(($runningSold / $totalSold) * 100, 1)
                : 0;

            return $product;
        });

        return [
            'lowStockLimit' => $limit,

            'selectedDailyDate' => $dailyDate,
            'selectedMonthPeriod' => $monthPeriod,
            'selectedMonthLabel' => Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y'),

            'dailyRevenue' => $dailyRevenue,
            'dailyTransactionCount' => $dailyTransactionCount,
            'dailyAverageTransaction' => $dailyAverageTransaction,
            'dailySoldPortions' => $dailySoldPortions,

            'monthlyRevenue' => $monthlyRevenue,
            'monthlyTransactionCount' => $monthlyTransactionCount,
            'monthlyAverageTransaction' => $monthlyAverageTransaction,
            'monthlySoldPortions' => $monthlySoldPortions,

            'remainingPortions' => $remainingPortions,
            'lowStockProductsCount' => $lowStockProductsCount,

            'recentTransactions' => $recentTransactions,
            'topProducts' => $topProducts,
            'stockProducts' => $stockProducts,
            'lowStockProducts' => $lowStockProducts,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard', $this->getDashboardData());
    }
}