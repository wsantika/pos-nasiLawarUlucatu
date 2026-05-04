<?php

namespace App\Livewire\Kasir;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Services\ThermalPrinterService;

#[Layout('components.layouts.app')]
#[Title('POS Kasir - POS Nasi Lawar Ulucatu')]
class PosIndex extends Component
{
    public $categories = [];
    public $products = [];
    public $selectedCategory = '';
    public $search = '';
    public $cart = [];
    public $subtotal = 0;
    public $discount = 0;
    public $tax = 0;
    public $total = 0;
    public $paymentMethod = 'cash';
    public $orderType = 'dine-in';
    public $tableNumber = '';
    public $paid = 0;
    public $change = 0;
    public $showPaymentModal = false;
    public $showSuccessModal = false;
    public $lastInvoice = '';

    public function mount()
    {
        $this->categories = Category::all();
        $this->loadProducts();
        $this->calculateTotal();
    }

    public function loadProducts()
    {
        $query = Product::with('category')
            ->active()
            ->where('stock', '>', 0);

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
        $product = Product::active()
            ->whereKey($productId)
            ->first();

        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Produk tidak tersedia atau sedang dinonaktifkan.');
            $this->loadProducts();

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

    public function updateQuantity($index, $action)
    {
        if (!isset($this->cart[$index])) {
            return;
        }

        if ($action === 'increase') {
            if ($this->cart[$index]['quantity'] < $this->cart[$index]['stock']) {
                $this->cart[$index]['quantity']++;
            }
        } elseif ($action === 'decrease') {
            if ($this->cart[$index]['quantity'] > 1) {
                $this->cart[$index]['quantity']--;
            }
        }

        $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['price'];
        $this->calculateTotal();
    }

    public function removeFromCart($index)
    {
        if (!isset($this->cart[$index])) {
            return;
        }

        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal = collect($this->cart)->sum('subtotal');

        $discount = (float) $this->discount;
        $tax = (float) $this->tax;

        $this->total = max(0, $this->subtotal - $discount + $tax);

        $this->calculateChange();
    }

    public function updatedDiscount()
    {
        $this->calculateTotal();
    }

    public function updatedTax()
    {
        $this->calculateTotal();
    }

    public function updatedOrderType()
    {
        if ($this->orderType === 'take-away') {
            $this->tableNumber = '';
        }
    }

    public function openPaymentModal()
    {
        if (empty($this->cart)) {
            return;
        }

        $this->showPaymentModal = true;
        $this->paid = $this->total;
        $this->calculateChange();
    }

    public function updatedPaid()
    {
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $paid = is_numeric($this->paid) ? (float) $this->paid : 0;
        $this->change = max(0, $paid - (float) $this->total);
    }

    public function processPayment()
    {
        $paid = (float) $this->paid;
        $total = (float) $this->total;

        if ($paid < $total) {
            session()->flash('error', 'Jumlah pembayaran kurang dari total transaksi.');
            return;
        }

        $this->validate([
            'orderType' => 'required|in:dine-in,take-away',
            'tableNumber' => $this->orderType === 'dine-in'
                ? 'required|string|max:20'
                : 'nullable|string|max:20',
            'paymentMethod' => 'required|in:cash,transfer,qris',
            'paid' => 'required|numeric|min:' . max(0, (float) $this->total),
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ], [
            'tableNumber.required' => 'Nomor meja wajib diisi untuk dine-in.',
            'paid.min' => 'Jumlah pembayaran kurang dari total transaksi.',
        ]);

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang masih kosong.');
            return;
        }

        if ((float) $this->discount > (float) $this->subtotal) {
            session()->flash('error', 'Diskon tidak boleh lebih besar dari subtotal.');
            return;
        }

        DB::beginTransaction();

        try {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'subtotal' => (float) $this->subtotal,
                'discount' => (float) $this->discount,
                'tax' => (float) $this->tax,
                'total' => $total,
                'paid' => $paid,
                'change' => (float) $this->change,
                'payment_method' => $this->paymentMethod,
                'order_type' => $this->orderType,
                'table_number' => $this->orderType === 'dine-in' ? $this->tableNumber : null,
                'payment_status' => 'success',
            ]);

            foreach ($this->cart as $item) {
                $product = Product::active()
                    ->whereKey($item['id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception('Produk "' . $item['name'] . '" sudah dinonaktifkan dan tidak bisa dibeli.');
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Stok produk "' . $product->name . '" tidak mencukupi.');
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            $transaction->load(['details.product', 'user']);

            try {
                app(ThermalPrinterService::class)->printCustomerReceipt($transaction);
                app(ThermalPrinterService::class)->printKitchenTicket($transaction);
            } catch (\Throwable $printError) {
                report($printError);

                session()->flash(
                    'error',
                    'Transaksi berhasil, tetapi struk gagal dicetak: ' . $printError->getMessage()
                );
            }

            $this->lastInvoice = $transaction->invoice_number ?? '';
            $this->showPaymentModal = false;
            $this->showSuccessModal = true;

            $this->resetTransaction();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetTransaction()
    {
        $this->cart = [];
        $this->subtotal = 0;
        $this->discount = 0;
        $this->tax = 0;
        $this->total = 0;
        $this->paid = 0;
        $this->change = 0;
        $this->paymentMethod = 'cash';
        $this->loadProducts();
        $this->orderType = 'dine-in';
        $this->tableNumber = '';
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
    }

    public function render()
    {
        return view('livewire.kasir.pos', [
            'categories' => $this->categories,
            'products' => $this->products,
            'cart' => $this->cart,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'total' => $this->total,
            'paymentMethod' => $this->paymentMethod,
            'paid' => $this->paid,
            'change' => $this->change,
            'showPaymentModal' => $this->showPaymentModal,
            'showSuccessModal' => $this->showSuccessModal,
            'lastInvoice' => $this->lastInvoice,
            'table_number' => $this->orderType === 'dine-in' ? $this->tableNumber : null,
            'payment_status' => 'success',
        ]);
    }
}