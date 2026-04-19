<div class="min-h-screen bg-slate-50">
    <div class="lg:pl-64">
        <main class="p-6">
            @if (session()->has('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Product List -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Search & Filter -->
                    <div class="bg-white rounded-xl p-4 border border-slate-200 card-shadow">
                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="flex-1 relative">
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    placeholder="Cari produk..."
                                    class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @forelse($products as $product)
                            <div wire:click="addToCart({{ $product->id }})"
                                class="bg-white rounded-xl p-4 border border-slate-200 card-shadow cursor-pointer hover:shadow-lg hover:border-slate-300 transition-all">
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-32 object-cover rounded-lg mb-3">
                                @else
                                    <div class="w-full h-32 bg-slate-100 rounded-lg flex items-center justify-center mb-3">
                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <h3 class="font-semibold text-slate-900 mb-1 text-sm">{{ $product->name }}</h3>
                                <p class="text-xs text-slate-500 mb-2">Stok: {{ $product->stock }}</p>
                                <p class="font-bold text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-slate-500">Tidak ada produk tersedia</p>
                            </div>
                        @endforelse
                    </div>
                </div>
              <!-- Cart -->
                <div class="bg-white rounded-xl border border-slate-200 card-shadow h-fit sticky top-24">
                    <div class="p-4 border-b border-slate-200">
                        <h3 class="font-bold text-slate-900">Keranjang</h3>
                    </div>

                    <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                        @forelse($cart as $index => $item)
                            <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900 text-sm">{{ $item['name'] }}</p>
                                    <p class="text-xs text-slate-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                            </div> 
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <p class="text-slate-500 text-sm">Keranjang kosong</p>
                            </div>
                        @endforelse          
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
