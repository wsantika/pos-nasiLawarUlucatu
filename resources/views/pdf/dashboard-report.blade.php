<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $jenis }} - Nasi Lawar Ulucatu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a1a1a; }

        .header {
            background-color: #b45309;
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p  { font-size: 11px; margin-top: 4px; opacity: 0.9; }

        .section { margin: 0 24px 18px 24px; }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #92400e;
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }

        /* Ringkasan cards */
        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 8px;
        }
        .summary-row { display: table-row; }
        .summary-card {
            display: table-cell;
            width: 25%;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
        }
        .summary-card .label { font-size: 10px; color: #78716c; margin-bottom: 4px; }
        .summary-card .value { font-size: 14px; font-weight: bold; color: #92400e; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; }
        th {
            background-color: #92400e;
            color: white;
            padding: 7px 10px;
            text-align: left;
            font-size: 11px;
        }
        td { padding: 6px 10px; font-size: 11px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background-color: #fffbeb; }

        /* Stok menipis */
        .stok-warning { color: #dc2626; font-weight: bold; }

        .footer {
            margin: 20px 24px 0 24px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 10px;
            color: #9ca3af;
            text-align: right;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <h1>Laporan {{ $jenis }} — Nasi Lawar Ulucatu</h1>
    <p>Periode: {{ $periode }}</p>
</div>

{{-- RINGKASAN --}}
<div class="section">
    <div class="section-title">Ringkasan Omzet & Transaksi</div>
    <table class="summary-grid">
        <tr class="summary-row">
            <td class="summary-card">
                <div class="label">Total Omzet</div>
                <div class="value">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            </td>
            <td class="summary-card">
                <div class="label">Jumlah Transaksi</div>
                <div class="value">{{ $jumlahTransaksi }}</div>
            </td>
            <td class="summary-card">
                <div class="label">Rata-rata Transaksi</div>
                <div class="value">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
            </td>
            <td class="summary-card">
                <div class="label">Total Porsi Terjual</div>
                <div class="value">{{ $totalPorsi }} porsi</div>
            </td>
        </tr>
    </table>
</div>

{{-- MENU TERLARIS --}}
<div class="section">
    <div class="section-title">Menu Terlaris</div>
    @if(count($menuSales) > 0)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Menu</th>
                <th>Jumlah Terjual (Porsi)</th>
            </tr>
        </thead>
        <tbody>
    @foreach($menuSales as $menu)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $menu->name }}</td>
        <td>{{ $menu->total_qty }} porsi</td>
    </tr>
    @endforeach
    </table>
    @else
        <p style="color:#9ca3af; font-style:italic;">Tidak ada data menu pada periode ini.</p>
    @endif
</div>

{{-- STOK MENIPIS --}}
<div class="section">
    <div class="section-title">Pantauan Stok / Sisa Porsi Menipis</div>
    @if($stokMenipis->count() > 0)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokMenipis as $produk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $produk->name }}</td>
                <td class="stok-warning">{{ $produk->stock }} porsi</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="color:#16a34a;">✓ Semua stok dalam kondisi aman.</p>
    @endif
</div>

{{-- FOOTER --}}
<div class="footer">
    Dicetak pada: {{ $tanggalCetak }} &nbsp;|&nbsp; Nasi Lawar Ulucatu — Sistem POS
</div>

</body>
</html>