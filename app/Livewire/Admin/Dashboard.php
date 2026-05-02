<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard & Laporan - POS Admin Panel')]
class Dashboard extends Component
{
    private const LOW_STOCK_LIMIT = 10;

    private function getDashboardData(): array
    {
        $today = today()->toDateString();
        $limit = self::LOW_STOCK_LIMIT;

        $transactionQuery = Transaction::query()
            ->whereDate('created_at', $today)
            ->where('payment_status', 'success');

        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $todayTransactions = (clone $transactionQuery)->count();
        $todayRevenue = (clone $transactionQuery)->sum('total');

        $averageTransaction = $todayTransactions > 0
            ? (int) floor($todayRevenue / $todayTransactions)
            : 0;

        $totalSoldToday = TransactionDetail::query()
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereDate('transactions.created_at', $today)
            ->where('transactions.payment_status', 'success')
            ->sum('transaction_details.quantity');

        $lowStockProductsCount = Product::active()
            ->where('stock', '<=', $limit)
            ->count();

        $monthlyRevenue = Transaction::query()
            ->whereYear('created_at', today()->year)
            ->whereMonth('created_at', today()->month)
            ->where('payment_status', 'success')
            ->sum('total');

        $recentTransactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->where('payment_status', 'success')
            ->latest()
            ->take(5)
            ->get();

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

        $topProducts = TransactionDetail::query()
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereDate('transactions.created_at', $today)
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

            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'todayTransactions' => $todayTransactions,
            'todayRevenue' => $todayRevenue,
            'averageTransaction' => $averageTransaction,
            'monthlyRevenue' => $monthlyRevenue,

            'totalSoldToday' => $totalSoldToday,
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