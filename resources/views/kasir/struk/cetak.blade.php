<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</title>

    <style>
        body {
            margin: 0;
            padding: 12px;
            background: #f1f5f9;
            font-family: 'Courier New', monospace;
        }

        .struk {
            width: 72mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 8px;
            border: 1px dashed #999;
            box-sizing: border-box;
        }

        .struk .title {
            font-family: 'Courier New', monospace;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.5;
            margin: 0 0 6px;
            white-space: normal;
            width: 100%;
            display: inline-block;
            color: #000000;
        }

        pre {
            font-family: 'Courier New', monospace;
            text-align: left;
            font-size: 16px;
            line-height: 1.6;
            width: 36ch;
            margin: 0;
            white-space: pre-wrap;
            color: #000000;
        }

        .action {
            width: 72mm;
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
                font-size: 18px;
                font-weight: 700;
                -webkit-text-stroke: 0.7px currentColor;
                text-shadow:
                    0.4px 0 0 currentColor,
                    -0.4px 0 0 currentColor,
                    0 0.4px 0 currentColor,
                    0 -0.4px 0 currentColor;
                line-height: 1.5;
                width: 36ch;
                white-space: pre-wrap;
            }

            .action { display: none; }
        }
    </style>
</head>
<body>

@php
    $subtotal = $pesanan->total_harga ?? 0;
    $metode   = $pesanan->metode_pembayaran ?? 'cash';

    $pajak = ($pesanan->pajak > 0)
        ? $pesanan->pajak
        : round($subtotal * 0.07);

    $biayaCard = ($pesanan->biaya_card > 0)
        ? $pesanan->biaya_card
        : (in_array($metode, ['card', 'qris']) ? round(($subtotal + $pajak) * 0.02) : 0);

    $totalBayar = ($pesanan->total_bayar > 0)
        ? $pesanan->total_bayar
        : ($subtotal + $pajak + $biayaCard);

    $bayarCash       = (int) ($pesanan->bayar_cash ?? 0);
    $bayarElektronik = (int) ($pesanan->bayar_elektronik ?? 0);

    $bayar     = $pesanan->bayar ?? $totalBayar;
    $kembalian = $pesanan->kembalian ?? 0;

    $isElektronik = in_array($metode, ['qris', 'card']);
    $isSplit = $isElektronik && $bayarCash > 0 && $bayarElektronik > 0;

    $labelElektronik = match ($metode) {
        'qris' => 'QRIS',
        'card' => 'CARD',
        'cash' => 'CASH',
        default => strtoupper($metode),
    };

    $labelMetode = $isSplit
        ? 'SPLIT CASH + ' . $labelElektronik
        : $labelElektronik;

    $labelFee = match ($metode) {
        'qris' => 'QRIS Fee',
        'card' => 'Card Fee',
        default => 'Fee',
    };
@endphp

<div class="struk">
    <pre>====================================</pre>
    <div class="title">
        <div>THE PANDE HILL</div>
        <div>JL. RAYA ULUWATU 188</div>
        <div>KEL PECATU KEC PECATU</div>
        <div>KUTA SELATAN</div>
        <div>BADUNG</div>
    </div>

    <pre>
====================================
Date     : {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') }}
Table   : {{ $pesanan->meja->nomor_meja ?? '-' }}
Cashier: {{ $pesanan->user->nama ?? '-' }}
------------------------------------
@foreach($pesanan->detailPesanan as $d)
@php
    $harga = $d->harga_pakai ?? $d->menu->harga ?? 0;
    $subtotalItem = $d->subtotal ?? ($harga * $d->jumlah);
@endphp
{{ mb_strimwidth($d->menu->nama_menu ?? '-', 0, 36, '..') }}
@if(!empty($d->catatan))
  *{{ mb_strimwidth($d->catatan, 0, 34, '..') }}
@endif
  {{ $d->jumlah }}x Rp{{ number_format($harga, 0, ',', '.') }}
     = Rp{{ number_format($subtotalItem, 0, ',', '.') }}
------------------------------------
@endforeach
Subtotal      : Rp{{ number_format($subtotal, 0, ',', '.') }}
Service Charge: Rp{{ number_format($pajak, 0, ',', '.') }}
@if($biayaCard > 0)
{{ str_pad($labelFee, 14, ' ', STR_PAD_RIGHT) }}: Rp{{ number_format($biayaCard, 0, ',', '.') }}
@endif
====================================
TOTAL  : Rp{{ number_format($totalBayar, 0, ',', '.') }}
@if($isSplit)
Cash   : Rp{{ number_format($bayarCash, 0, ',', '.') }}
{{ str_pad($labelElektronik, 7, ' ', STR_PAD_RIGHT) }}: Rp{{ number_format($bayarElektronik, 0, ',', '.') }}
@else
Paid   : Rp{{ number_format($bayar, 0, ',', '.') }}
@endif
Change : Rp{{ number_format($kembalian, 0, ',', '.') }}
====================================
  Metode/Method: {{ $labelMetode }}
====================================
        Thank You!
      See you again :)
====================================
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

<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>

</body>
</html>