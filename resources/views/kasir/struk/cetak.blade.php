{{-- struk_thermal.blade.php --}}
@php
    $subtotal   = $pesanan->total_harga ?? 0;
    $metode     = $pesanan->metode_pembayaran ?? 'cash';
    $pajak      = ($pesanan->pajak > 0) ? $pesanan->pajak : round($subtotal * 0.07);
    $biayaCard  = ($pesanan->biaya_card > 0) ? $pesanan->biaya_card : ($metode === 'card' ? round(($subtotal + $pajak) * 0.02) : 0);
    $totalBayar = ($pesanan->total_bayar > 0) ? $pesanan->total_bayar : ($subtotal + $pajak + $biayaCard);
@endphp
<pre style="font-family: 'Courier New', monospace; font-size: 12px; width: 42ch;">
================================
     PANDE HILL GARDEN VIEW
        Garden View Restaurant
================================
Tgl : {{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y H:i') }}
Meja: {{ $pesanan->meja->nomor_meja }}
Kasir: {{ $pesanan->user->nama }}
--------------------------------
@foreach($pesanan->detailPesanan as $d)
{{ str_pad($d->menu->nama_menu, 20) }}
  {{ $d->jumlah }}x {{ number_format($d->menu->harga,0,',','.') }}
  = Rp {{ number_format($d->subtotal,0,',','.') }}
@endforeach
--------------------------------
Subtotal : Rp {{ number_format($subtotal,0,',','.') }}
Service  : Rp {{ number_format($pajak,0,',','.') }}
@if($biayaCard > 0)
Biaya Card: Rp {{ number_format($biayaCard,0,',','.') }}
@endif
================================
TOTAL: Rp {{ number_format($totalBayar,0,',','.') }}
================================
      Terima Kasih!
</pre>