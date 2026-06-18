<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Produk Terlaris - {{ $periodeLabel ?? $tanggal }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', Arial, sans-serif; font-size: 12px; color: #0b1527; background: #ffffff; padding: 32px; }
        .header { display: flex; align-items: flex-start; justify-content: space-between; padding-bottom: 16px; margin-bottom: 20px; border-bottom: 2px solid #0b1527; }
        .brand { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 600; color: #0b1527; }
        .brand span { display: inline-block; width: 6px; height: 6px; background: #d4af37; border-radius: 50%; margin-left: 3px; margin-bottom: 4px; vertical-align: middle; }
        .brand-sub { font-size: 10px; color: #d4af37; letter-spacing: 0.2em; text-transform: uppercase; margin-top: 3px; }
        .header-right { text-align: right; }
        .header-right h2 { font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 600; color: #0b1527; margin-bottom: 3px; }
        .header-right p { font-size: 11px; color: rgba(13, 27, 42, 0.5); }
        .summary { display: flex; gap: 12px; margin-bottom: 20px; }
        .summary-box { flex: 1; border: 1px solid rgba(13, 27, 42, 0.12); border-radius: 8px; padding: 12px 14px; }
        .summary-box.dark { background: #0b1527; border-color: #0b1527; }
        .summary-label { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.15em; color: rgba(13, 27, 42, 0.5); margin-bottom: 4px; }
        .summary-box.dark .summary-label { color: rgba(244, 237, 228, 0.55); }
        .summary-value { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 500; color: #0b1527; }
        .summary-box.dark .summary-value { color: #f4ede4; }
        .summary-sub { font-size: 10px; color: rgba(13, 27, 42, 0.45); margin-top: 2px; }
        .summary-box.dark .summary-sub { color: rgba(244, 237, 228, 0.45); }
        .section-title { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.15em; color: #d4af37; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        thead th { background: #0b1527; color: #f4ede4; padding: 8px 10px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid rgba(13, 27, 42, 0.07); font-size: 11px; color: #0b1527; vertical-align: middle; }
        tbody tr:nth-child(even) td { background: rgba(13, 27, 42, 0.02); }
        .text-right { text-align: right; }
        .fw-bold { font-weight: 600; }
        .rank-badge { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 6px; background: #0b1527; color: #f4ede4; font-weight: 600; }
        tfoot td { padding: 10px; font-weight: 600; font-size: 12px; border-top: 2px solid #0b1527; }
        tfoot td:last-child { font-family: 'Playfair Display', serif; font-size: 14px; color: #d4af37; }
        .print-footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid rgba(13, 27, 42, 0.1); display: flex; justify-content: space-between; font-size: 10px; color: rgba(13, 27, 42, 0.4); }
        @media print { body { padding: 20px; } @page { margin: 0; } }
    </style>
</head>
<body onload="window.print()">

@php
    $menuTerlaris = $menuTerlaris ?? collect();
    $totalItemTerjual = $menuTerlaris->sum('total_terjual');
    $totalPendapatanMenu = $menuTerlaris->sum('total_pendapatan');
    $menuPalingLaris = $menuTerlaris->first();
@endphp

<div class="header">
    <div>
        <div class="brand">The Pande Hill<span></span></div>
        <div class="brand-sub">Garden View Restaurant</div>
    </div>
    <div class="header-right">
        <h2>Laporan Produk Terlaris</h2>
        <p>Periode: {{ $periodeLabel ?? \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
        <p>Dicetak: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
    </div>
</div>

<div class="summary">
    <div class="summary-box">
        <div class="summary-label">Total Item Terjual</div>
        <div class="summary-value">{{ number_format($totalItemTerjual, 0, ',', '.') }}</div>
        <div class="summary-sub">porsi/item</div>
    </div>
    <div class="summary-box">
        <div class="summary-label">Menu Paling Laris</div>
        <div class="summary-value">{{ $menuPalingLaris->nama_menu ?? '-' }}</div>
        <div class="summary-sub">{{ $menuPalingLaris ? number_format($menuPalingLaris->total_terjual, 0, ',', '.') . ' terjual' : 'belum ada transaksi' }}</div>
    </div>
    <div class="summary-box dark">
        <div class="summary-label">Pendapatan Produk</div>
        <div class="summary-value">Rp {{ number_format($totalPendapatanMenu, 0, ',', '.') }}</div>
        <div class="summary-sub">berdasarkan subtotal menu</div>
    </div>
</div>

<div class="section-title">Ranking Produk Terlaris</div>

<table>
    <thead>
        <tr>
            <th>Rank</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th class="text-right">Jumlah Terjual</th>
            <th class="text-right">Transaksi</th>
            <th class="text-right">Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($menuTerlaris as $item)
            <tr>
                <td><span class="rank-badge">{{ $loop->iteration }}</span></td>
                <td class="fw-bold">{{ $item->nama_menu }}</td>
                <td>{{ $item->nama_kategori ?? '-' }}</td>
                <td class="text-right fw-bold">{{ number_format($item->total_terjual, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->total_transaksi, 0, ',', '.') }}</td>
                <td class="text-right fw-bold">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; color:rgba(13,27,42,0.4); padding:20px;">
                    Belum ada data produk terjual pada periode ini
                </td>
            </tr>
        @endforelse
    </tbody>
    @if($menuTerlaris->count() > 0)
        <tfoot>
            <tr>
                <td colspan="3">Total Keseluruhan</td>
                <td class="text-right">{{ number_format($totalItemTerjual, 0, ',', '.') }}</td>
                <td class="text-right">-</td>
                <td class="text-right">Rp {{ number_format($totalPendapatanMenu, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    @endif
</table>

<div class="print-footer">
    <span>The Pande Hill Garden View Restaurant</span>
    <span>Sistem Informasi Restoran © {{ date('Y') }}</span>
</div>

</body>
</html>
