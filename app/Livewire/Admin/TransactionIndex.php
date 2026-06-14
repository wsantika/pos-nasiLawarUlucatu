<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Services\TransactionPaymentService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Transaksi - POS Nasi Lawar Ulucatu')]
class TransactionIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $paymentMethodFilter = '';
    public $paymentStatusFilter = '';
    public $selectedTransaction;
    public $showDetailModal = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethodFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatusFilter()
    {
        $this->resetPage();
    }

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

    public function resetFilter(): void
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'paymentMethodFilter', 'paymentStatusFilter']);
    }

    public function confirmPayment($id): void
    {
        try {
            $transaction = Transaction::findOrFail($id);
            app(TransactionPaymentService::class)->markAsSuccess($transaction);

            session()->flash('success', 'Pembayaran berhasil dikonfirmasi.');

            if ($this->selectedTransaction && $this->selectedTransaction->id === $transaction->id) {
                $this->selectedTransaction = Transaction::with(['details.product', 'user'])->find($transaction->id);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancelPayment($id): void
    {
        try {
            $transaction = Transaction::findOrFail($id);
            app(TransactionPaymentService::class)->markAsFailed($transaction);

            session()->flash('success', 'Pembayaran pending berhasil dibatalkan.');

            if ($this->selectedTransaction && $this->selectedTransaction->id === $transaction->id) {
                $this->selectedTransaction = Transaction::with(['details.product', 'user'])->find($transaction->id);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Transaction::with('user')
            ->where('invoice_number', 'like', '%' . $this->search . '%');

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->paymentMethodFilter) {
            $query->where('payment_method', $this->paymentMethodFilter);
        }

        if ($this->paymentStatusFilter) {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        $transactions = (clone $query)->latest()->paginate(10);
        $totalRevenue = (clone $query)->where('payment_status', 'success')->sum('total');
        $filteredCount = (clone $query)->count();

        return view('livewire.admin.transaction', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'filteredCount' => $filteredCount,
        ]);
    }
}
