<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app')]
#[Title('Produk - POS Nasi Lawar Ulucatu')]
class ProductIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $categoryFilter = '';
    public $productId;
    public $name = '';
    public $sku = '';
    public $category_id = '';
    public $description = '';
    public $price = '';
    public $stock = '';
    public $image;
    public $oldImage;
    public $is_active = true;
    public $isEdit = false;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|min:3',
        'sku' => 'required|unique:products,sku',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'description' => 'nullable',
        'image' => 'nullable|image|max:2048',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama produk wajib diisi',
        'sku.required' => 'SKU wajib diisi',
        'sku.unique' => 'SKU sudah digunakan',
        'category_id.required' => 'Kategori wajib dipilih',
        'price.required' => 'Harga wajib diisi',
        'price.numeric' => 'Harga harus berupa angka',
        'stock.required' => 'Stok wajib diisi',
        'stock.integer' => 'Stok harus berupa angka',
        'image.image' => 'File harus berupa gambar',
        'image.max' => 'Ukuran gambar maksimal 2MB',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'sku', 'category_id', 'description', 'price', 'stock', 'image', 'oldImage', 'productId', 'isEdit', 'is_active']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->rules['sku'] = 'required|unique:products,sku,' . $this->productId;
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
            $data['image'] = $imagePath;

            if ($this->isEdit && $this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
        }

        Product::updateOrCreate(['id' => $this->productId], $data);
        session()->flash('message', 'Produk berhasil ditambahkan');

        $this->closeModal();
    }

    public function toggleActive($id)
    {
        $product = Product::find($id);
        $product->update(['is_active' => !$product->is_active]);
        session()->flash('message', $product->is_active ? 'Produk diaktifkan' : 'Produk dinonaktifkan');
    }

    public function render()
    {
        $query = Product::with('category')
            ->where('name', 'like', '%' . $this->search . '%');

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('livewire.admin.product', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}