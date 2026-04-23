<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Transaksi - POS Nasi Lawar Ulucatu')]
class TransactionIndex extends Component
{
    public $dateFrom = '';
    public $dateTo = '';
    public $paymentMethodFilter = '';
    public $selectedTransaction;
    public $showDetailModal = false;

    public function viewDetail($id)
    {
        $this->selectedTransaction = Transaction::with(['details.product', 'user'])->find($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransaction = null;
    }

    public function render()
    {
        $query = Transaction::with('user');

        $transactions = $query->latest()->paginate(10);

        $todayTotal = Transaction::whereDate('created_at', today())->sum('total');
        $monthTotal = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $transactionCount = Transaction::count();

        return view('livewire.admin.transaction', [
            'transactions' => $transactions,
            'todayTotal' => $todayTotal,
            'monthTotal' => $monthTotal,
            'transactionCount' => $transactionCount
        ]);
    }
}