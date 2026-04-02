<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Transaksi - POS Nasi Lawar Ulucatu')]
class TransactionIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.transaction');
    }
}