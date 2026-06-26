<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Menu - Pande Hill Garden View</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Georgia, "Times New Roman", serif;
            background: #f1f5f9;
            padding: 20px;
            color: #000;
        }

        .action-bar {
            width: 100%;
            max-width: 680px;
            margin: 0 auto 16px;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn {
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .btn-back  { background: #e5e7eb; color: #111827; }
        .btn-print { background: #16a34a; color: #ffffff; }

        .page {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
            background: #fff;
            border: 1px dashed #bbb;
            padding: 32px 36px 40px;
            position: relative;
        }

        /* Watermark background */
        .page::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 55%;
            height: 100%;
            background-image: url("/images/watermark.jpg");
            background-size: cover;
            background-position: center top;
            opacity: 0.13;
            pointer-events: none;
            z-index: 0;
        }

        .page > * {
            position: relative;
            z-index: 1;
        }

        /* Header */
        .resto-header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 2px solid #000;
            page-break-after: avoid;
            break-after: avoid;
        }

        .resto-name {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 0.04em;
        }

        .resto-sub {
            font-size: 13px;
            color: #555;
            margin-top: 3px;
            font-style: italic;
        }

        .cetak-info {
            font-size: 11px;
            margin-top: 8px;
            color: #888;
            font-family: Arial, sans-serif;
        }

        /* Kategori */
        .kategori-block {
            margin-bottom: 28px;
        }

        .kategori-title {
            font-size: 19px;
            font-weight: 900;
            color: #1a1a1a;
            margin-bottom: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            page-break-after: avoid;
            break-after: avoid;
        }

        /* Item */
        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .menu-item-left { flex: 1; min-width: 0; }

        .menu-item-name {
            font-size: 15px;
            font-weight: 700;
            color: #7a5c00;
            line-height: 1.3;
            text-transform: capitalize;
        }

        .menu-item-desc {
            font-size: 12px;
            color: #555;
            margin-top: 3px;
            font-style: italic;
            line-height: 1.5;
        }

        .menu-item-price {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            white-space: nowrap;
            padding-top: 1px;
        }

        .menu-item-habis .menu-item-name { color: #999; }
        .menu-item-habis .menu-item-price { color: #bbb; text-decoration: line-through; }

        .habis-tag {
            display: inline-block;
            font-size: 9px;
            font-weight: 900;
            background: #fee2e2;
            color: #b91c1c;
            border-radius: 4px;
            padding: 1px 6px;
            margin-left: 6px;
            font-family: Arial, sans-serif;
            vertical-align: middle;
        }

        .service-note {
            margin-top: 28px;
            border-top: 2px solid #000;
            padding-top: 12px;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .page-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #aaa;
            font-family: Arial, sans-serif;
            text-transform: none;
            font-weight: 400;
            letter-spacing: 0;
        }

@media print {
    @page {
        size: A4 portrait;
        margin: 10mm 12mm;
    }

    html, body {
        background: #fff;
        padding: 0;
        margin: 0;
    }

    .action-bar {
        display: none !important;
    }

    .page {
        width: 100%;
        max-width: none;
        border: none;
        padding: 0;
        margin: 0;
    }

    .page::before {
        position: absolute; /* ini sebelumnya salah: abssolute */
        top: 0;
        right: 0;
        width: 55%;
        height: 100%;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .resto-header {
        break-after: avoid;
        page-break-after: avoid;
        margin-bottom: 16px;
    }

    .kategori-block {
        break-inside: auto !important;
        page-break-inside: auto !important;
        margin-bottom: 22px;
    }

    .kategori-title {
        break-after: avoid;
        page-break-after: avoid;
    }

    .menu-item {
        break-inside: avoid;
        page-break-inside: avoid;
        margin-bottom: 10px;
    }

    .service-note {
        break-inside: avoid;
        page-break-inside: avoid;
    }
}
    </style>
</head>
<body>

@php
    // Format angka jadi format Rupiah, mis. 25000 -> "IDR. 25"
    $formatRupiah = fn($harga) => 'IDR. ' . number_format($harga / 1000, 0, ',', '.');

    // Kelompokkan menu berdasarkan nama kategori, lalu urutkan nama kategori A-Z
    $menusByKategori = $menu->groupBy(fn($item) => $item->kategori->nama_kategori ?? 'Tanpa Kategori')
                             ->sortKeys();

    // Hitung statistik menu
    $totalMenu     = $menu->count();
    $totalTersedia = $menu->where('status', 'tersedia')->count();
    $totalHabis    = $totalMenu - $totalTersedia;
@endphp

<div class="action-bar">
<button class="btn btn-back" onclick="window.close()">
    Tutup
</button>
    <button class="btn btn-print" onclick="window.print()">&#128424; Cetak</button>
</div>

<div class="page">

    <div class="resto-header">
        <div class="resto-name">Pande Hill Garden View</div>
        <div class="resto-sub">Garden View Restaurant</div>
        <div class="cetak-info">
            Dicetak: {{ now()->timezone('Asia/Makassar')->format('d/m/Y H:i') }} WITA
        </div>
    </div>

    @foreach ($menusByKategori as $kategori => $items)
        <div class="kategori-block">
            <div class="kategori-title">{{ $kategori }}</div>

            @foreach ($items as $menu)
                @php
                    $isHabis = $menu->status !== 'tersedia';
                @endphp

                <div class="menu-item {{ $isHabis ? 'menu-item-habis' : '' }}">
                    <div class="menu-item-left">
                        <div class="menu-item-name">
                            {{ $menu->nama_menu }}
                            @if($isHabis)
                                <span class="habis-tag">HABIS</span>
                            @endif
                        </div>
                        @if(!empty($menu->deskripsi))
                            <div class="menu-item-desc">{{ $menu->deskripsi }}</div>
                        @endif
                    </div>
                    <div class="menu-item-price">
                        {{ $formatRupiah($menu->harga) }}
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="service-note">
        Subject to 7% Service
        <div class="page-footer">
            <span>Pande Hill Garden View</span>
            <span>Dicetak oleh: {{ auth()->user()->nama ?? auth()->user()->name ?? '-' }}</span>
        </div>
    </div>

</div>

</body>
</html>