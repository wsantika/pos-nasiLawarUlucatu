<?php

namespace App\Livewire\Kasir;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('POS Kasir - POS Nasi Lawar Ulucatu')]
class PosIndex extends Component
{
    public function render()
    {
        return view('livewire.kasir.pos');
    }
}