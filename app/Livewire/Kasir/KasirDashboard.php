<?php

namespace App\Livewire\Kasir;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.kasir')]
#[Title('Kasir - POS Nasi Lawar Ulucatu')]
class KasirDashboard extends Component
{
    public $todayTransactions;
    public $todayRevenue;

    public function mount()
    {
        $this->todayTransactions = Transaction::whereDate('created_at', today())->count();
        $this->todayRevenue = Transaction::whereDate('created_at', today())->sum('total');
    }

    public function render()
    {
        return view('livewire.kasir.dashboard');
    }
}

