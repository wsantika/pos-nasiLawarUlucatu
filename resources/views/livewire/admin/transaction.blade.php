<div class="min-h-screen bg-slate-50">
    @include('livewire.admin.components.sidebar-admin')

    <!-- Main Content -->
    <div class="lg:pl-64">

        <!-- Content -->
        <main class="p-6">
            @if (session()->has('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg border border-slate-200 card-shadow">
                <!-- Header -->
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col space-y-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Riwayat Transaksi</h2>
                            <p class="text-sm text-slate-500 mt-1">Daftar semua transaksi yang telah dilakukan</p>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-3">
                            <div class="relative flex-1">
                                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari invoice..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-slate-600">Dari:</span>
                                <input type="date" wire:model.live="dateFrom"
                                    class="px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-transparent" title="Tanggal Awal">
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-slate-600">Sampai:</span>
                                <input type="date" wire:model.live="dateTo"
                                    class="px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-transparent" title="Tanggal Akhir">
                            </div>
                            <select wire:model.live="paymentMethodFilter"
                                class="px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-transparent bg-white">
                                <option value="">Semua Metode</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                            <select wire:model.live="paymentStatusFilter"
                                class="px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-transparent bg-white">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                            <button type="button" wire:click="resetFilter"
                                class="px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-500">Total Omzet</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-500">Jumlah Transaksi</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">
                            {{ $filteredCount }}
                        </p>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Invoice
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Kasir
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Total
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Metode
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $transaction->invoice_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900">
                                            {{ $transaction->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $transaction->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900">{{ $transaction->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-900">Rp
                                            {{ number_format($transaction->total, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ ucfirst($transaction->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $transaction->payment_status === 'success' ? 'bg-green-50 text-green-700 border-green-200' : ($transaction->payment_status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200') }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button wire:click="viewDetail({{ $transaction->id }})"
                                                class="inline-flex items-center px-3 py-1.5 border border-slate-300 text-xs font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Detail
                                            </button>

                                            @if ($transaction->payment_status === 'pending')
                                                <button wire:click="confirmPayment({{ $transaction->id }})"
                                                    wire:confirm="Pastikan pembayaran sudah masuk. Konfirmasi transaksi ini?"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-slate-900 hover:bg-slate-800 transition-colors">
                                                    Konfirmasi
                                                </button>

                                                <button wire:click="cancelPayment({{ $transaction->id }})"
                                                    wire:confirm="Batalkan transaksi pending ini?"
                                                    class="inline-flex items-center px-3 py-1.5 border border-red-200 text-xs font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors">
                                                    Batalkan
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        <p class="text-slate-500 font-medium">Tidak ada transaksi ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <!-- Detail Modal -->
    @if ($showDetailModal && $selectedTransaction)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-50" wire:click="closeDetailModal">
                </div>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <!-- Header -->
                    <div class="bg-white px-6 py-4 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900">Detail Transaksi</h3>
                            <button type="button" wire:click="closeDetailModal"
                                class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="bg-white px-6 py-4">
                        <!-- Transaction Info -->
                        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <div>
                                <p class="text-sm text-slate-500">Invoice</p>
                                <p class="font-semibold text-slate-900">{{ $selectedTransaction->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Tanggal</p>
                                <p class="font-semibold text-slate-900">
                                    {{ $selectedTransaction->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Kasir</p>
                                <p class="font-semibold text-slate-900">{{ $selectedTransaction->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Tipe Order</p>
                                <p class="font-semibold text-slate-900">
                                    {{ $selectedTransaction->order_type === 'dine-in' ? 'Dine-in' : 'Take-away' }}
                                </p>
                            </div>
                            @if ($selectedTransaction->order_type === 'dine-in')
                                <div>
                                    <p class="text-sm text-slate-500">Nomor Meja</p>
                                    <p class="font-semibold text-slate-900">
                                        {{ $selectedTransaction->table_number ?? '-' }}
                                    </p>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm text-slate-500">Metode Pembayaran</p>
                                <p class="font-semibold text-slate-900">
                                    {{ ucfirst($selectedTransaction->payment_method) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Status Pembayaran</p>
                                <p class="font-semibold text-slate-900">
                                    {{ ucfirst($selectedTransaction->payment_status) }}
                                </p>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-slate-900 mb-3">Item Produk</h4>
                            <div class="space-y-3">
                                @foreach ($selectedTransaction->details as $detail)
                                    <div
                                        class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-900">{{ $detail->product->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $detail->quantity }} x Rp
                                                {{ number_format($detail->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <p class="font-semibold text-slate-900">Rp
                                            {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-slate-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Subtotal</span>
                                <span class="font-medium text-slate-900">Rp
                                    {{ number_format($selectedTransaction->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if ($selectedTransaction->discount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Diskon</span>
                                    <span class="font-medium text-red-600">- Rp
                                        {{ number_format($selectedTransaction->discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($selectedTransaction->tax > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Pajak</span>
                                    <span class="font-medium text-slate-900">Rp
                                        {{ number_format($selectedTransaction->tax, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg font-semibold pt-2 border-t border-slate-200">
                                <span class="text-slate-900">Total</span>
                                <span class="text-slate-900">Rp
                                    {{ number_format($selectedTransaction->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Dibayar</span>
                                <span class="font-medium text-slate-900">Rp
                                    {{ number_format($selectedTransaction->paid, 0, ',', '.') }}</span>
                            </div>
                            @if ($selectedTransaction->change > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Kembalian</span>
                                    <span class="font-medium text-green-600">Rp
                                        {{ number_format($selectedTransaction->change, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-slate-50 px-6 py-4 flex flex-wrap justify-end gap-3 border-t border-slate-200">
                        @if ($selectedTransaction->payment_status === 'pending')
                            <button type="button" wire:click="confirmPayment({{ $selectedTransaction->id }})"
                                wire:confirm="Pastikan pembayaran sudah masuk. Konfirmasi transaksi ini?"
                                class="px-4 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors font-medium">
                                Konfirmasi Pembayaran
                            </button>

                            <button type="button" wire:click="cancelPayment({{ $selectedTransaction->id }})"
                                wire:confirm="Batalkan transaksi pending ini?"
                                class="px-4 py-2.5 border border-red-200 text-red-700 rounded-lg hover:bg-red-50 transition-colors font-medium">
                                Batalkan Pembayaran
                            </button>
                        @endif

                        <button type="button" wire:click="closeDetailModal"
                            class="px-4 py-2.5 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
