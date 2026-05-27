<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - {{ $tanggal }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', Arial, sans-serif;
            font-size: 12px;
            color: #0b1527;
            background: #ffffff;
            padding: 32px;
        }

        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding-bottom: 16px;
            margin-bottom: 20px;
            border-bottom: 2px solid #0b1527;
        }

        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 600;
            color: #0b1527;
        }

        .brand span {
            display: inline-block;
            width: 6px;
            height: 6px;
            background: #d4af37;
            border-radius: 50%;
            margin-left: 3px;
            margin-bottom: 4px;
            vertical-align: middle;
        }

        .brand-sub {
            font-size: 10px;
            color: #d4af37;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .header-right {
            text-align: right;
        }

        .header-right h2 {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 600;
            color: #0b1527;
            margin-bottom: 3px;
        }

        .header-right p {
            font-size: 11px;
            color: rgba(13, 27, 42, 0.5);
        }

        .summary {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .summary-box {
            flex: 1;
            border: 1px solid rgba(13, 27, 42, 0.12);
            border-radius: 8px;
            padding: 12px 14px;
        }

        .summary-box.dark {
            background: #0b1527;
            border-color: #0b1527;
        }

        .summary-label {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: rgba(13, 27, 42, 0.5);
            margin-bottom: 4px;
        }

        .summary-box.dark .summary-label {
            color: rgba(244, 237, 228, 0.55);
        }

        .summary-value {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 500;
            color: #0b1527;
        }

        .summary-box.dark .summary-value {
            color: #f4ede4;
        }

        .summary-sub {
            font-size: 10px;
            color: rgba(13, 27, 42, 0.45);
            margin-top: 2px;
        }

        .summary-box.dark .summary-sub {
            color: rgba(244, 237, 228, 0.45);
        }

        .section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #d4af37;
            margin-bottom: 8px;
        }

        .breakdown {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .breakdown-box {
            flex: 1;
            border: 1px solid rgba(13, 27, 42, 0.1);
            border-radius: 8px;
            padding: 10px 14px;
        }

        .breakdown-title {
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 4px;
            color: #0b1527;
        }

        .breakdown-amount {
            font-family: 'Playfair Display', serif;
            font-size: 15px;
            color: #0b1527;
        }

        .breakdown-count {
            font-size: 10px;
            color: rgba(13, 27, 42, 0.45);
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        thead th {
            background: #0b1527;
            color: #f4ede4;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
        }

        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid rgba(13, 27, 42, 0.07);
            font-size: 11px;
            color: #0b1527;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) td {
            background: rgba(13, 27, 42, 0.02);
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: 600;
        }

        tfoot td {
            padding: 10px;
            font-weight: 600;
            font-size: 12px;
            border-top: 2px solid #0b1527;
        }

        tfoot td:last-child {
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            color: #d4af37;
        }

        .print-footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid rgba(13, 27, 42, 0.1);
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: rgba(13, 27, 42, 0.4);
        }

        @media print {
            body {
                padding: 20px;
            }

            @page {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @php
        $totalTransaksi = $pesanan->count();

        $hitungTotalFinal = function ($p) {
            $subtotal = (int) ($p->total_harga ?? 0);
            $service = (int) ($p->pajak ?? 0) + (int) ($p->biaya_card ?? 0);
            $totalFinal = (int) ($p->total_bayar ?? 0);

            return $totalFinal > 0 ? $totalFinal : ($subtotal + $service);
        };

        $tunai = $pesanan->where('metode_pembayaran', 'cash');
        $qris  = $pesanan->where('metode_pembayaran', 'qris');
        $card  = $pesanan->where('metode_pembayaran', 'card');

        $totalTunai  = $tunai->sum(fn ($p) => $hitungTotalFinal($p));
        $jumlahTunai = $tunai->count();

        $totalQris  = $qris->sum(fn ($p) => $hitungTotalFinal($p));
        $jumlahQris = $qris->count();

        $totalCard  = $card->sum(fn ($p) => $hitungTotalFinal($p));
        $jumlahCard = $card->count();

        $totalSubtotal = $pesanan->sum(fn ($p) => (int) ($p->total_harga ?? 0));
        $totalService  = $pesanan->sum(fn ($p) => (int) ($p->pajak ?? 0) + (int) ($p->biaya_card ?? 0));
        $totalSemua    = $pesanan->sum(fn ($p) => $hitungTotalFinal($p));
    @endphp

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="brand">The Pande Hill<span></span></div>
            <div class="brand-sub">Garden View Restaurant</div>
        </div>

        <div class="header-right">
            <h2>Laporan Penjualan</h2>
            <p>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            <p>Dicetak: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary">
        <div class="summary-box">
            <div class="summary-label">Total Transaksi</div>
            <div class="summary-value">{{ $totalTransaksi }}</div>
            <div class="summary-sub">transaksi</div>
        </div>

        <div class="summary-box dark">
            <div class="summary-label">Total Pendapatan</div>
            <div class="summary-value">Rp {{ number_format($totalSemua, 0, ',', '.') }}</div>
            <div class="summary-sub">sudah termasuk pajak & service</div>
        </div>
    </div>

    {{-- Breakdown Pembayaran --}}
    <div class="section-title">Breakdown Pembayaran</div>

    <div class="breakdown">
        <div class="breakdown-box">
            <div class="breakdown-title">💵 Tunai</div>
            <div class="breakdown-amount">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
            <div class="breakdown-count">{{ $jumlahTunai }} transaksi</div>
        </div>

        <div class="breakdown-box">
            <div class="breakdown-title">📱 QRIS</div>
            <div class="breakdown-amount">Rp {{ number_format($totalQris, 0, ',', '.') }}</div>
            <div class="breakdown-count">{{ $jumlahQris }} transaksi</div>
        </div>

        <div class="breakdown-box">
            <div class="breakdown-title">💳 Card</div>
            <div class="breakdown-amount">Rp {{ number_format($totalCard, 0, ',', '.') }}</div>
            <div class="breakdown-count">{{ $jumlahCard }} transaksi</div>
        </div>
    </div>

    {{-- Detail Transaksi --}}
    <div class="section-title">Detail Transaksi</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Meja</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Service</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pesanan as $p)
                @php
                    $subtotal = (int) ($p->total_harga ?? 0);
                    $service = (int) ($p->pajak ?? 0) + (int) ($p->biaya_card ?? 0);
                    $totalFinal = (int) ($p->total_bayar ?? 0);

                    if ($totalFinal <= 0) {
                        $totalFinal = $subtotal + $service;
                    }

                    $metode = match($p->metode_pembayaran) {
                        'cash' => 'Tunai',
                        'qris' => 'QRIS',
                        'card' => 'Card',
                        default => '-',
                    };
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                    <td>Meja {{ $p->meja->nomor_meja ?? '-' }}</td>
                    <td>{{ $p->user->nama ?? '-' }}</td>
                    <td>{{ $metode }}</td>
                    <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($service, 0, ',', '.') }}</td>
                    <td class="text-right fw-bold">Rp {{ number_format($totalFinal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:rgba(13,27,42,0.4); padding:20px;">
                        Tidak ada transaksi pada tanggal ini
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if($pesanan->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="5">Total Keseluruhan</td>
                    <td class="text-right">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalService, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- Footer --}}
    <div class="print-footer">
        <span>The Pande Hill Garden View Restaurant</span>
        <span>Sistem Informasi Restoran © {{ date('Y') }}</span>
    </div>

</body>
</html>