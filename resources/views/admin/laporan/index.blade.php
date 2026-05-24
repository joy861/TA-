@extends('layouts.admin')

@section('content')

@php
    $totalTransaksi = $pesanan->count();

    $totalTunai  = $pesanan->where('metode_pembayaran', 'cash')->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07))));
    $jumlahTunai = $pesanan->where('metode_pembayaran', 'cash')->count();

    $totalQris   = $pesanan->where('metode_pembayaran', 'qris')->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07))));
    $jumlahQris  = $pesanan->where('metode_pembayaran', 'qris')->count();

    $totalCard   = $pesanan->where('metode_pembayaran', 'card')->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07)) + ($p->biaya_card ?? 0)));
    $jumlahCard  = $pesanan->where('metode_pembayaran', 'card')->count();

    $totalPendapatan = $pesanan->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07)) + ($p->biaya_card ?? 0)));
@endphp

<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
    <div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1" style="color:#60a5fa; letter-spacing:0.15em;">ANALISIS</p>
        <h1 class="text-2xl font-black tracking-tight" style="color:#1e3a5f; letter-spacing:-0.5px;">Laporan Penjualan</h1>
        <p class="text-sm mt-0.5" style="color:rgba(30,58,95,0.5);">Rekap transaksi harian restoran</p>
    </div>
    <a href="{{ route('laporan.cetak', $tanggal ?? date('Y-m-d')) }}" target="_blank"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex-shrink-0"
       style="background:#1e3a5f; color:#fff;"
       onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
       onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
        <i class="bi bi-printer"></i> Cetak Laporan
    </a>
</div>

{{-- FILTER --}}
<div class="rounded-2xl p-5 mb-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <form action="{{ route('laporan.filter') }}" method="POST">
        @csrf
        <p class="text-xs font-bold tracking-widest uppercase mb-3" style="color:#60a5fa; letter-spacing:0.15em;">FILTER TANGGAL</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="date" name="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}"
                   class="flex-1 rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                   style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                   onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                   onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all"
                    style="background:#1e3a5f; color:#fff; border:none;"
                    onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
                    onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
                <i class="bi bi-search"></i> Tampilkan
            </button>
        </div>
    </form>
</div>

{{-- BENTO STATS --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
    <div class="rounded-2xl p-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">TOTAL TRANSAKSI</div>
        <div class="text-4xl font-black" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalTransaksi }}</div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(30,58,95,0.4);">transaksi pada tanggal ini</div>
    </div>
    <div class="rounded-2xl p-5" style="background:#1e3a5f;">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(255,255,255,0.4); letter-spacing:0.12em;">TOTAL PENDAPATAN</div>
        <div class="font-black leading-tight" style="color:#fff; letter-spacing:-1px;">
            <span class="text-sm font-bold" style="color:rgba(255,255,255,0.5);">Rp </span>
            <span class="text-3xl">{{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(255,255,255,0.4);">sudah termasuk pajak &amp; service</div>
    </div>
</div>

{{-- BREAKDOWN --}}
<div class="rounded-2xl p-5 mb-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <h3 class="text-sm font-black mb-0.5" style="color:#1e3a5f;">Breakdown Pembayaran</h3>
    <p class="text-xs mb-4" style="color:rgba(30,58,95,0.4);">Pendapatan berdasarkan metode pembayaran (sudah termasuk service)</p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-4 transition-all" style="border:1.5px solid rgba(30,58,95,0.08); background:#eef2ff;"
             onmouseover="this.style.borderColor='#60a5fa'" onmouseout="this.style.borderColor='rgba(30,58,95,0.08)'">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black"
                     style="background:#1e3a5f; color:#60a5fa;">Rp</div>
                <span class="text-sm font-black" style="color:#1e3a5f;">Tunai</span>
            </div>
            <div class="text-2xl font-black mb-1" style="color:#1e3a5f;">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
            <div class="text-xs font-semibold" style="color:rgba(30,58,95,0.5);">{{ $jumlahTunai }} transaksi</div>
        </div>
        <div class="rounded-xl p-4 transition-all" style="border:1.5px solid rgba(30,58,95,0.08); background:#eef2ff;"
             onmouseover="this.style.borderColor='#60a5fa'" onmouseout="this.style.borderColor='rgba(30,58,95,0.08)'">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black"
                     style="background:#60a5fa; color:#1e3a5f;">QR</div>
                <span class="text-sm font-black" style="color:#1e3a5f;">QRIS</span>
            </div>
            <div class="text-2xl font-black mb-1" style="color:#1e3a5f;">Rp {{ number_format($totalQris, 0, ',', '.') }}</div>
            <div class="text-xs font-semibold" style="color:rgba(30,58,95,0.5);">{{ $jumlahQris }} transaksi</div>
        </div>
        <div class="rounded-xl p-4 transition-all" style="border:1.5px solid rgba(30,58,95,0.08); background:#eef2ff;"
             onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='rgba(30,58,95,0.08)'">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black"
                     style="background:rgba(99,102,241,0.15); color:#6366f1;">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <span class="text-sm font-black" style="color:#1e3a5f;">Card</span>
            </div>
            <div class="text-2xl font-black mb-1" style="color:#1e3a5f;">Rp {{ number_format($totalCard, 0, ',', '.') }}</div>
            <div class="text-xs font-semibold" style="color:rgba(30,58,95,0.5);">{{ $jumlahCard }} transaksi</div>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="rounded-2xl overflow-hidden" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
        <div>
            <h3 class="text-sm font-black" style="color:#1e3a5f;">Detail Transaksi</h3>
            <p class="text-xs mt-0.5" style="color:rgba(30,58,95,0.4);">{{ $tanggal ?? date('Y-m-d') }}</p>
        </div>
        <span class="text-xs font-bold px-3 py-1.5 rounded-lg" style="background:#eef2ff; color:#1e3a5f;">
            {{ $totalTransaksi }} transaksi
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:rgba(30,58,95,0.02); border-bottom:1px solid rgba(30,58,95,0.06);">
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase w-12" style="color:rgba(30,58,95,0.35);">No</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Tanggal</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Meja</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Kasir</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Metode</th>
                    <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Subtotal</th>
                    <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Service</th>
                    <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                @php
                    $pSubtotal  = $p->total_harga ?? 0;
                    $pPajak     = $p->pajak ?? round($pSubtotal * 0.07);
                    $pBiayaCard = $p->biaya_card ?? 0;
                    $pTotal     = $p->total_bayar ?? ($pSubtotal + $pPajak + $pBiayaCard);
                @endphp
                <tr style="border-bottom:1px solid rgba(30,58,95,0.04);"
                    onmouseover="this.style.background='rgba(30,58,95,0.02)'"
                    onmouseout="this.style.background='transparent'">
                    <td class="px-5 py-3.5 text-xs" style="color:rgba(30,58,95,0.35);">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3.5 text-xs font-semibold" style="color:rgba(30,58,95,0.5);">{{ $p->tanggal }}</td>
                    <td class="px-5 py-3.5 font-black" style="color:#1e3a5f;">Meja {{ $p->meja->nomor_meja }}</td>
                    <td class="px-5 py-3.5 font-semibold" style="color:rgba(30,58,95,0.6);">{{ $p->user->nama }}</td>
                    <td class="px-5 py-3.5">
                        @if($p->metode_pembayaran == 'cash')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:rgba(34,197,94,0.1); color:#15803d;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#22c55e;"></span> Tunai
                            </span>
                        @elseif($p->metode_pembayaran == 'qris')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:#eef2ff; color:#1e3a5f;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#60a5fa;"></span> QRIS
                            </span>
                        @elseif($p->metode_pembayaran == 'card')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:rgba(99,102,241,0.1); color:#6366f1;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#6366f1;"></span> Card
                            </span>
                        @else
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eef2ff; color:rgba(30,58,95,0.5);">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right font-semibold" style="color:rgba(30,58,95,0.6);">
                        Rp {{ number_format($pSubtotal, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-semibold" style="color:rgba(30,58,95,0.6);">
                        Rp {{ number_format($pPajak + $pBiayaCard, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-black" style="color:#1e3a5f;">
                        Rp {{ number_format($pTotal, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-sm" style="color:rgba(30,58,95,0.3);">
                        <i class="bi bi-calendar-x text-3xl block mb-2"></i>
                        Tidak ada transaksi pada tanggal ini
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($pesanan->count() > 0)
            <tfoot>
                <tr style="background:#eef2ff; border-top:2px solid rgba(30,58,95,0.1);">
                    <td colspan="5" class="px-5 py-3.5 text-sm font-black" style="color:#1e3a5f;">Total Keseluruhan</td>
                    <td class="px-5 py-3.5 text-right font-semibold text-sm" style="color:rgba(30,58,95,0.6);">
                        Rp {{ number_format($pesanan->sum('total_harga'), 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-semibold text-sm" style="color:rgba(30,58,95,0.6);">
                        Rp {{ number_format($pesanan->sum(fn($p) => ($p->pajak ?? round($p->total_harga * 0.07)) + ($p->biaya_card ?? 0)), 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-black text-base" style="color:#1e3a5f;">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection