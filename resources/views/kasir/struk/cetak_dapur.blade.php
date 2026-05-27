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
            line-height: 1.25;
            width: 32ch;
            margin: 0;
            white-space: pre-wrap;
            color: #000000;
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

        .btn-back {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-data {
            background: #1e3a5f;
            color: #ffffff;
        }

        .btn-print {
            background: #16a34a;
            color: #ffffff;
        }

        @media print {
            html,
            body {
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
                font-size: 11px;
                line-height: 1.25;
                width: 32ch;
                white-space: pre-wrap;
            }

            .action {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="struk">
<pre>
==============================
         ORDER DAPUR
==============================
Tanggal : {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') }}
Meja    : {{ $pesanan->meja->nomor_meja ?? '-' }}
Kasir   : {{ $pesanan->user->nama ?? '-' }}
------------------------------
DAFTAR PESANAN
------------------------------
@foreach($pesanan->detailPesanan as $d)
{{ $d->menu->nama_menu ?? '-' }}
Jumlah : {{ $d->jumlah }}x
@if(!empty($d->tipe_harga))
Tipe   : {{ strtoupper($d->tipe_harga) }}
@endif
------------------------------
@endforeach
==============================
    Mohon segera diproses
==============================
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