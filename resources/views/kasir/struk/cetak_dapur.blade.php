<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Dapur</title>

    <style>
        body {
            margin: 0;
            padding: 12px;
            background: #f1f5f9;
            font-family: 'Courier New', monospace;
        }

        .struk {
            width: 58mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 6px;
            border: 1px dashed #999;
            box-sizing: border-box;
        }

        pre {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.3;
            width: 32ch;
            margin: 0;
            white-space: pre-wrap;
            color: #000000;
        }

        .tgl {
            font-size: 14px;
        }

        .action {
            width: 58mm;
            margin: 16px auto 0;
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn {
            border: none;
            padding: 9px 11px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .btn-back  { background: #e5e7eb; color: #111827; }
        .btn-data  { background: #1e3a5f; color: #ffffff; }
        .btn-print { background: #16a34a; color: #ffffff; }

        @media print {
            html, body {
                width: 58mm;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            .struk {
                width: 58mm;
                margin: 0;
                padding: 3mm;
                border: none;
                box-sizing: border-box;
            }

            pre {
                font-family: 'Courier New', monospace;
                font-size: 20px;
                line-height: 1.3;
                width: 32ch;
                white-space: pre-wrap;

                font-weight: 700;
                color: #000;
                -webkit-text-stroke: 0.5px currentColor;
                text-shadow:
                    0.3px 0 0 currentColor,
                    -0.3px 0 0 currentColor,
                    0 0.3px 0 currentColor,
                    0 -0.3px 0 currentColor;
            }

            .tgl {
                font-size: 18px;
            }

            .action { display: none; }
        }
    </style>
</head>
<body>

@php
    // Pisahkan itemsBaru jadi: menu benar-benar baru, dan tambahan qty
    $menuBaru  = $itemsBaru->filter(fn($i) => ($i['jenis'] ?? null) !== 'TAMBAHAN');
    $tambahQty = $itemsBaru->filter(fn($i) => ($i['jenis'] ?? null) === 'TAMBAHAN');

    // Helper: wrap teks catatan agar tidak melebihi 32 karakter per baris
    // Prefix "          " (10 spasi) untuk baris lanjutan agar rata setelah "Catatan : "
    $wrapCatatan = function (?string $catatan, int $lebar = 22, string $prefix = '          '): string {
        if (empty($catatan)) return '';
        $baris  = wordwrap($catatan, $lebar, "\n", true);
        $barisArr = explode("\n", $baris);
        $hasil  = 'Catatan : ' . array_shift($barisArr);
        foreach ($barisArr as $b) {
            $hasil .= "\n" . $prefix . $b;
        }
        return $hasil;
    };
@endphp

<div class="struk">
<pre>
==============================
{{ $isUpdatePesanan ? '     UPDATE PESANAN DAPUR' : '         ORDER DAPUR' }}
==============================
Tanggal : <span class="tgl">{{ now()->timezone('Asia/Makassar')->format('d/m/Y H:i') }}</span>
Meja    : {{ $pesanan->meja->nomor_meja ?? '-' }}
Kasir   : {{ $pesanan->user->nama ?? '-' }}
@if(($isUpdatePesanan || (isset($isReprint) && $isReprint)) && $itemsLama->count() > 0)
------------------------------
[ X ] SUDAH DIMASAK
------------------------------
@foreach($itemsLama as $item)
{{ $item['nama'] }}
Jumlah  : {{ $item['jumlah'] }}x
@if(!empty($item['catatan']))
{{ $wrapCatatan($item['catatan']) }}
@endif
------------------------------
@endforeach
@endif
@if($menuBaru->count() > 0)
------------------------------
[ ! ] HARUS DIMASAK
------------------------------
@foreach($menuBaru as $item)
>> {{ $item['nama'] }}
Jumlah  : {{ $item['jumlah'] }}x
@if(!empty($item['catatan']))
{{ $wrapCatatan($item['catatan']) }}
@endif
------------------------------
@endforeach
@endif
@if($tambahQty->count() > 0)
------------------------------
[ + ] TAMBAH QUANTITY
------------------------------
@foreach($tambahQty as $item)
>> {{ $item['nama'] }}
Tambah  : +{{ $item['jumlah'] }}x
@if(!empty($item['catatan']))
{{ $wrapCatatan($item['catatan']) }}
@endif
------------------------------
@endforeach
@endif
==============================
    Mohon segera diproses
==============================
@if(isset($isReprint) && $isReprint)
================================
         REPRINT
================================
@endif
</pre>
</div>

<div class="action">
    <button type="button" class="btn btn-back" onclick="history.back()">
        Kembali
    </button>

    <button type="button" class="btn btn-data" onclick="window.location.href='{{ route('pesanan.index') }}'">
        Data Pesanan
    </button>

    <button type="button" class="btn btn-print" onclick="window.print()">
        Cetak
    </button>
</div>

</body>
</html>