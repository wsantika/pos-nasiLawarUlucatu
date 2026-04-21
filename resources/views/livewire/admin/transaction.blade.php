<div class="min-h-screen bg-slate-50">
    @include('livewire.admin.components.sidebar-admin')

    <!-- Main Content -->
    <div class="lg:pl-64">

        <!-- Content -->
        <main class="p-6">
            <div class="bg-white rounded-lg border border-slate-200 card-shadow">
                <!-- Header -->
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col space-y-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Riwayat Transaksi</h2>
                            <p class="text-sm text-slate-500 mt-1">Daftar semua transaksi yang telah dilakukan</p>
                        </div>
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
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>