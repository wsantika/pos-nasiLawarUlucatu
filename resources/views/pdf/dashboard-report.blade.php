<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $jenis }} - Nasi Lawar Ulucatu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #f8fafc;
            color: #0f172a;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }

        .page {
            background: #ffffff;
            margin: 18px;
            padding: 22px;
        }

        .header {
            background: #0f172a;
            border-radius: 14px;
            color: #ffffff;
            padding: 22px 24px;
            margin-bottom: 22px;
        }
        .header h1 { font-size: 20px; font-weight: bold; letter-spacing: -0.2px; }
        .header p  { color: #cbd5e1; font-size: 11px; margin-top: 5px; }
        .header .badge {
            background: #ecfdf5;
            border-radius: 999px;
            color: #047857;
            display: inline-block;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 4px 10px;
            text-transform: uppercase;
        }

        .section { margin-bottom: 20px; }
        .section-title {
            border-bottom: 1px solid #e2e8f0;
            color: #0f172a;
            font-weight: bold;
            font-size: 13px;
            letter-spacing: -0.1px;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }
        .section-title span {
            border-left: 4px solid #10b981;
            padding-left: 8px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 8px;
        }
        .summary-row { display: table-row; }
        .summary-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            display: table-cell;
            padding: 10px 12px;
            text-align: center;
            width: 25%;
        }
        .summary-card .label { color: #64748b; font-size: 10px; margin-bottom: 5px; }
        .summary-card .value { color: #0f172a; font-size: 14px; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; }
        th {
            background-color: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            color: #334155;
            font-size: 11px;
            padding: 8px 10px;
            text-align: left;
        }
        td { border-bottom: 1px solid #e2e8f0; font-size: 11px; padding: 8px 10px; }
        tr:nth-child(even) td { background-color: #f8fafc; }

        .muted-message {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #64748b;
            font-style: italic;
            padding: 12px;
        }
        .safe-message {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            color: #047857;
            padding: 12px;
        }
        .stock-pill {
            background: #fef2f2;
            border-radius: 999px;
            color: #b91c1c;
            display: inline-block;
            font-weight: bold;
            padding: 3px 8px;
        }

        .footer {
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 10px;
            margin-top: 24px;
            padding-top: 12px;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="page">
    {{-- HEADER --}}
    <div class="header">
        <div class="badge">POS Report</div>
        <h1>Laporan {{ $jenis }} - Nasi Lawar Ulucatu</h1>
        <p>Periode {{ $periode }} • Ringkasan transaksi, menu terlaris, dan pantauan stok.</p>
    </div>

    {{-- RINGKASAN --}}
    <div class="section">
    <div class="section-title"><span>Ringkasan Omzet & Transaksi</span></div>
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
    <div class="section-title"><span>Menu Terlaris</span></div>
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
        </tbody>
    </table>
    @else
        <p class="muted-message">Tidak ada data menu pada periode ini.</p>
    @endif
    </div>

    {{-- STOK MENIPIS --}}
    <div class="section">
    <div class="section-title"><span>Pantauan Stok / Sisa Porsi Menipis</span></div>
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
                <td><span class="stock-pill">{{ $produk->stock }} porsi</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="safe-message">Semua stok dalam kondisi aman.</p>
    @endif
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Dicetak pada: {{ $tanggalCetak }} &nbsp;|&nbsp; Nasi Lawar Ulucatu - Sistem POS
    </div>
</div>

</body>
</html>
