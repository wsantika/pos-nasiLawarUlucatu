<?php

namespace App\Livewire\Kasir;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('POS Kasir - POS Nasi Lawar Ulucatu')]
class PosIndex extends Component
{
    public $categories = [];
    public $selectedCategory = '';
    public $products = []; 
    public $search = ''; 
    public $cart = [];

    public function mount()
    {
        $this->categories = Category::all();
        
        $this->loadProducts(); 
    }

    public function loadProducts()
    {
        $query = Product::with('category')->where('stock', '>', 0);

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $this->products = $query->get();
    }

    public function updatedSelectedCategory()
    {
        $this->loadProducts();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

     public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            return;
        }

        $existingIndex = null;

        foreach ($this->cart as $index => $item) {
            if ($item['id'] === $productId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            if ($this->cart[$existingIndex]['quantity'] < $product->stock) {
                $this->cart[$existingIndex]['quantity']++;
                $this->cart[$existingIndex]['subtotal'] = $this->cart[$existingIndex]['quantity'] * $this->cart[$existingIndex]['price'];
            }
        } else {
            $this->cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
                'stock' => $product->stock
            ];
        }

        $this->calculateTotal();
    }

    public function render()
    {
        return view('livewire.kasir.pos', [
            'categories' => $this->categories,
            'products' => $this->products
        ]);
    }
}