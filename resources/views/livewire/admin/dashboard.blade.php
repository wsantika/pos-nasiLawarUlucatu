<div>
    @include('livewire.includes.navbar')
    @include('livewire.admin.components.sidebar-admin')

    <div class="p-6">
        <main class="lg:pl-64 space-y-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Dasbor & Laporan</h1>
                    <p class="text-sm text-slate-500">
                        Rekapitulasi omzet, pantauan stok, dan laporan menu terlaris hari ini.
                    </p>
                </div>
            </div>

            @if ($lowStockProductsCount > 0)
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100">
                            <svg class="h-5 w-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="font-semibold text-amber-900">Peringatan stok menipis</h3>
                            <p class="text-sm text-amber-800">
                                Ada {{ $lowStockProductsCount }} produk dengan stok kurang dari atau sama dengan
                                {{ $lowStockLimit }} porsi.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Omzet Harian</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Berdasarkan transaksi sukses hari ini
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Transaksi Harian</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        {{ $todayTransactions }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Rata-rata Rp {{ number_format($averageTransaction, 0, ',', '.') }} per transaksi
                    </p>
                </div>


                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Porsi Terjual</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        {{ $totalSoldToday }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Total item terjual hari ini
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Omzet Bulanan</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Berdasarkan hasil transaksi sukses bulan ini
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <section class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 p-5">
                        <h2 class="text-lg font-bold text-slate-900">Laporan Menu Terlaris - Pareto</h2>
                        <p class="text-sm text-slate-500">
                            Urutan menu berdasarkan jumlah porsi terjual dan kontribusi kumulatif hari ini.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Rank
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Menu
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Terjual
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Omzet
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Kontribusi
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Kumulatif
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($topProducts as $product)
                                    <tr class="{{ $product->cumulative_percentage <= 80 ? 'bg-green-50/40' : '' }}">
                                        <td class="px-5 py-4 text-sm font-bold text-slate-900">
                                            #{{ $product->rank }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <p class="text-sm font-semibold text-slate-900">
                                                {{ $product->name }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ $product->category_name ?? '-' }}
                                            </p>
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-700">
                                            {{ $product->total_sold }} porsi
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-700">
                                            Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-700">
                                            {{ number_format($product->percentage, 1, ',', '.') }}%
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-200">
                                                    <div class="h-full rounded-full bg-slate-900"
                                                        style="width: {{ min(100, $product->cumulative_percentage) }}%">
                                                    </div>
                                                </div>

                                                <span class="text-sm text-slate-700">
                                                    {{ number_format($product->cumulative_percentage, 1, ',', '.') }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500">
                                            Belum ada data penjualan hari ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 p-5">
                        <h2 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h2>
                        <p class="text-sm text-slate-500">
                            Transaksi sukses hari ini.
                        </p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentTransactions as $transaction)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $transaction->invoice_number }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            {{ $transaction->user->name ?? '-' }}
                                            •
                                            {{ $transaction->created_at->format('H:i') }}
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                        {{ strtoupper($transaction->payment_method) }}
                                    </span>
                                </div>

                                <p class="mt-3 text-sm font-bold text-slate-900">
                                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                </p>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-slate-500">
                                Belum ada transaksi hari ini.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 p-5">
                        <h2 class="text-lg font-bold text-slate-900">Pantauan Sisa Porsi</h2>
                        <p class="text-sm text-slate-500">
                            Daftar produk aktif dengan stok paling sedikit.
                        </p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($stockProducts as $product)
                            @php
                                $stockPercent = min(100, ((int) $product->stock / max(((int) $lowStockLimit * 2), 1)) * 100);
                            @endphp

                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $product->name }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            {{ $product->category->name ?? '-' }}
                                        </p>
                                    </div>

                                    @if ($product->stock <= 0)
                                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">
                                            Habis
                                        </span>
                                    @elseif ($product->stock <= $lowStockLimit)
                                        <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">
                                            Menipis
                                        </span>
                                    @else
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                            Aman
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 flex items-center gap-3">
                                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-200">
                                        <div class="h-full rounded-full bg-slate-900" style="width: {{ $stockPercent }}%">
                                        </div>
                                    </div>

                                    <p class="w-20 text-right text-sm font-bold text-slate-900">
                                        {{ $product->stock }} porsi
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-slate-500">
                                Belum ada produk aktif.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 p-5">
                        <h2 class="text-lg font-bold text-slate-900">Daftar Stok Menipis</h2>
                        <p class="text-sm text-slate-500">
                            Produk dengan stok kurang dari atau sama dengan {{ $lowStockLimit }}.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Produk
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Kategori
                                    </th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Stok
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($lowStockProducts as $product)
                                    <tr>
                                        <td class="px-5 py-4 text-sm font-semibold text-slate-900">
                                            {{ $product->name }}
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-600">
                                            {{ $product->category->name ?? '-' }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <span
                                                class="rounded-full {{ $product->stock <= 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }} px-2 py-1 text-xs font-semibold">
                                                {{ $product->stock }} porsi
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-8 text-center text-sm text-slate-500">
                                            Tidak ada stok menipis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>
</div>