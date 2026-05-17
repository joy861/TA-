<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran - Meja {{ $pesanan->meja->nomor_meja }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            width: 320px;
            margin: 0 auto;
            padding: 20px 16px;
            color: #1e3a5f;
            background: #fff;
            font-size: 12px;
        }

        /* Header */
        .struk-header {
            text-align: center;
            padding-bottom: 14px;
            border-bottom: 2px dashed rgba(30,58,95,0.2);
            margin-bottom: 14px;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .brand-logo {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: #1e3a5f;
            color: #60a5fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 11px;
        }
        .brand-name {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #1e3a5f;
        }
        .struk-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2.5px;
            color: rgba(30,58,95,0.5);
            text-transform: uppercase;
            margin-top: 2px;
        }
        .struk-subtitle {
            font-size: 9px;
            color: rgba(30,58,95,0.4);
            margin-top: 4px;
        }

        /* Info */
        .info-block {
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 2px dashed rgba(30,58,95,0.2);
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding: 3px 0;
        }
        .info-label {
            color: rgba(30,58,95,0.55);
            font-weight: 500;
        }
        .info-value {
            color: #1e3a5f;
            font-weight: 700;
            text-align: right;
        }

        /* Items */
        .items-section {
            margin-bottom: 14px;
        }
        .section-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #60a5fa;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .item {
            margin-bottom: 8px;
        }
        .item-name {
            font-size: 11.5px;
            font-weight: 600;
            color: #1e3a5f;
            margin-bottom: 2px;
        }
        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            color: rgba(30,58,95,0.55);
        }
        .item-qty {
            font-weight: 500;
        }
        .item-price {
            font-weight: 600;
            color: #1e3a5f;
        }

        /* Total */
        .total-block {
            background: #1e3a5f;
            color: #fff;
            padding: 14px 14px;
            border-radius: 12px;
            margin-bottom: 14px;
        }
        .total-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .total-amount {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        .total-amount .rp {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            margin-right: 3px;
        }

        /* Footer */
        .struk-footer {
            text-align: center;
            padding-top: 10px;
            border-top: 2px dashed rgba(30,58,95,0.2);
        }
        .footer-thanks {
            font-size: 12px;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 3px;
        }
        .footer-note {
            font-size: 9px;
            color: rgba(30,58,95,0.45);
            line-height: 1.5;
        }
        .footer-stamp {
            display: inline-block;
            margin-top: 8px;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: rgba(30,58,95,0.4);
            padding: 4px 10px;
            border: 1px solid rgba(30,58,95,0.15);
            border-radius: 999px;
        }

        @media print {
            body { padding: 8px; }
            @page { margin: 0; size: 80mm auto; }
            .total-block { background: #1e3a5f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="struk-header">
        <div class="brand">
            <div class="brand-logo">PH</div>
            <div class="brand-name">Pande Hill</div>
        </div>
        <div class="struk-title">Struk Pembayaran</div>
        <div class="struk-subtitle">Garden View Restaurant</div>
    </div>

    {{-- INFO --}}
    <div class="info-block">
        <div class="info-row">
            <span class="info-label">Tanggal</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('d M Y, H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Meja</span>
            <span class="info-value">Meja {{ $pesanan->meja->nomor_meja }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Kasir</span>
            <span class="info-value">{{ $pesanan->user->nama }}</span>
        </div>
        @if($pesanan->metode_pembayaran ?? false)
        <div class="info-row">
            <span class="info-label">Metode</span>
            <span class="info-value">{{ $pesanan->metode_pembayaran == 'cash' ? 'Tunai' : 'QRIS' }}</span>
        </div>
        @endif
    </div>

    {{-- ITEMS --}}
    <div class="items-section">
        <div class="section-label">Detail Pesanan</div>
        @foreach($pesanan->detailPesanan as $d)
        <div class="item">
            <div class="item-name">{{ $d->menu->nama_menu }}</div>
            <div class="item-detail">
                <span class="item-qty">{{ $d->jumlah }}x &nbsp; Rp {{ number_format($d->menu->harga, 0, ',', '.') }}</span>
                <span class="item-price">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TOTAL --}}
    <div class="total-block">
        <div class="total-label">Total Pembayaran</div>
        <div class="total-amount">
            <span class="rp">Rp</span>{{ number_format($pesanan->total_harga, 0, ',', '.') }}
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="struk-footer">
        <div class="footer-thanks">Terima Kasih</div>
        <div class="footer-note">
            Semoga puas dengan pelayanan kami<br>
            Sampai jumpa kembali ✦
        </div>
        <div class="footer-stamp">{{ \Carbon\Carbon::now()->format('d M Y · H:i') }}</div>
    </div>

<script>
window.onload = function () {
    window.print();
};

window.onafterprint = function () {
    // Setelah print, kembali ke halaman show struk
    window.location.href = "{{ route('struk.show', $pesanan->id_pesanan) }}";
};

// Fallback jika onafterprint tidak terpicu (mobile/beberapa browser)
setTimeout(function () {
    window.location.href = "{{ route('struk.show', $pesanan->id_pesanan) }}";
}, 4000);
</script>

</body>
</html>