<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Banner -->
    <div class="bg-slate-900 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background: radial-gradient(circle at 70% 50%, white 0%, transparent 60%)"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm">{{ now()->format('l, d F Y') }} — Siap melayani pelanggan?</p>
            </div>
            <div class="hidden sm:flex items-center justify-center w-16 h-16 bg-white/10 rounded-2xl">
                <i class="fas fa-cash-register text-3xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Stats Hari Ini -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
        <!-- Transaksi Hari Ini -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-slate-500">Transaksi Hari Ini</p>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-blue-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $todayTransactions }}</p>
            <p class="text-xs text-slate-400 mt-1">transaksi tercatat hari ini</p>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-slate-500">Pendapatan Hari Ini</p>
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins text-green-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">total penjualan hari ini</p>
        </div>
    </div>

    <!-- Tombol Utama — Buka POS -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-store text-slate-600 text-2xl"></i>
        </div>
        <h2 class="text-lg font-semibold text-slate-900 mb-1">Mulai Transaksi</h2>
        <p class="text-sm text-slate-500 mb-5">Buka kasir untuk melayani pelanggan dan mencatat transaksi.</p>
        <a href="{{ route('kasir.pos') }}"
            class="inline-flex items-center space-x-2 bg-slate-900 text-white px-6 py-3 rounded-xl font-medium hover:bg-slate-800 transition-colors">
            <i class="fas fa-cash-register"></i>
            <span>Buka Kasir / POS</span>
        </a>
    </div>
</div>