@extends('layouts.kasir')

@section('content')

<style>
    .receipt-card {
        background: #fff;
        border: 1px solid rgba(30,58,95,0.08);
        border-radius: 22px;
        overflow: hidden;
    }

    .receipt-header {
        background: #1e3a5f;
        padding: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .receipt-header::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: rgba(96,165,250,0.15);
        right: -60px;
        top: -70px;
    }

    .receipt-logo {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #60a5fa;
        color: #1e3a5f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 16px;
        flex-shrink: 0;
    }

    .receipt-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .receipt-info-box {
        background: #eef2ff;
        border-radius: 12px;
        padding: 12px 14px;
    }

    .receipt-label {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(30,58,95,0.5);
        margin-bottom: 4px;
    }

    .receipt-value {
        font-size: 14px;
        font-weight: 900;
        color: #1e3a5f;
    }

    .receipt-total-box {
        background: #eef2ff;
        border-radius: 14px;
        padding: 16px 18px;
    }

    .receipt-total-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(30,58,95,0.5);
    }

    .receipt-total-value {
        font-size: 28px;
        font-weight: 900;
        color: #1e3a5f;
        letter-spacing: -1px;
    }

    .receipt-breakdown-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: rgba(30,58,95,0.55);
        font-weight: 600;
        padding: 4px 0;
    }

    .receipt-breakdown-row.final {
        color: #1e3a5f;
        font-size: 13px;
        font-weight: 800;
        padding-top: 10px;
        margin-top: 6px;
        border-top: 1px dashed rgba(30,58,95,0.15);
    }

    @media (max-width: 768px) {
        .receipt-info-grid { grid-template-columns: 1fr; }
        .receipt-total-value { font-size: 22px; }
    }
</style>

@php
    $backUrl = \Illuminate\Support\Facades\Route::has('pesanan.index')
        ? route('pesanan.index')
        : url('kasir/pesanan');

    $subtotal   = $pesanan->total_harga ?? 0;
    $metode     = $pesanan->metode_pembayaran ?? 'cash';
    $pajak      = ($pesanan->pajak > 0) ? $pesanan->pajak : round($subtotal * 0.07);
    $biayaCard  = ($pesanan->biaya_card > 0) ? $pesanan->biaya_card : ($metode === 'card' ? round(($subtotal + $pajak) * 0.02) : 0);
    $totalBayar = ($pesanan->total_bayar > 0) ? $pesanan->total_bayar : ($subtotal + $pajak + $biayaCard);
@endphp

<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">BUKTI TRANSAKSI</p>
        <h1 class="kasir-page-title">Struk Pembayaran</h1>
        <div class="kasir-page-subtitle">Bukti pembayaran pesanan pelanggan</div>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ $backUrl }}" class="kasir-btn kasir-btn-ghost">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <a href="{{ route('struk.cetak', $pesanan->id_pesanan) }}" target="_blank" class="kasir-btn kasir-btn-success">
            <i class="bi bi-printer"></i>
            <span>Cetak Struk</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    <div class="xl:col-span-2">
        <div class="receipt-card">

            <div class="receipt-header">
                <div class="relative z-10 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="receipt-logo">PH</div>
                        <div>
                            <div class="text-xl font-black leading-tight" style="letter-spacing:-0.5px;">Pande Hill</div>
                            <div class="text-xs mt-0.5" style="color:rgba(255,255,255,0.5);">Garden View Restaurant</div>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full"
                             style="background:rgba(34,197,94,0.2); color:#86efac;">
                            <span class="w-1.5 h-1.5 rounded-full" style="background:#22c55e;"></span>
                            <span class="text-xs font-bold">Pembayaran Berhasil</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5">

                <div class="receipt-info-grid mb-5">
                    <div class="receipt-info-box">
                        <div class="receipt-label">Tanggal</div>
                        <div class="receipt-value">
                            {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div class="receipt-info-box">
                        <div class="receipt-label">Meja</div>
                        <div class="receipt-value">Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</div>
                    </div>
                    <div class="receipt-info-box">
                        <div class="receipt-label">Kasir</div>
                        <div class="receipt-value">{{ $pesanan->user->nama ?? '-' }}</div>
                    </div>
                </div>

                <div class="rounded-xl overflow-hidden mb-5" style="border:1px solid rgba(30,58,95,0.06);">
                    <div class="kasir-table-wrap">
                        <table class="kasir-table" style="min-width:auto;">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detailPesanan as $d)
                                    @php
                                        $harga        = $d->harga_pakai ?? $d->menu->harga ?? 0;
                                        $jumlah       = $d->jumlah ?? 0;
                                        $subtotalItem = $d->subtotal ?? ($harga * $jumlah);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-bold">{{ $d->menu->nama_menu ?? '-' }}</div>
                                            @if(!empty($d->catatan))
                                                <div style="font-size:11px; color:rgba(30,58,95,0.5); margin-top:2px;">
                                                    📝 {{ $d->catatan }}
                                                </div>
                                            @endif
                                        </td>
                                        <td style="color:rgba(30,58,95,0.6);">Rp{{ number_format($harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="kasir-badge kasir-badge-info">{{ $jumlah }}x</span>
                                        </td>
                                        <td class="font-black">Rp{{ number_format($subtotalItem, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="receipt-total-box">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <div class="receipt-total-label">Total Pembayaran</div>
                            <div class="text-xs mt-1" style="color:rgba(30,58,95,0.5);">Total akhir pesanan pelanggan</div>
                        </div>
                        <div class="receipt-total-value">
                            <span style="font-size:13px; font-weight:700; color:rgba(30,58,95,0.5); margin-right:3px;">Rp</span>{{ number_format($totalBayar, 0, ',', '.') }}
                        </div>
                    </div>

                    <div style="border-top:1px solid rgba(30,58,95,0.1); margin-top:14px; padding-top:12px;">
                        <div class="receipt-breakdown-row">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="receipt-breakdown-row">
                            <span>Pajak (7%)</span>
                            <span>Rp{{ number_format($pajak, 0, ',', '.') }}</span>
                        </div>
                        @if($biayaCard > 0)
                        <div class="receipt-breakdown-row">
                            <span>Biaya Card (2%)</span>
                            <span>Rp{{ number_format($biayaCard, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="receipt-breakdown-row final">
                            <span>Total</span>
                            <span>Rp{{ number_format($totalBayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="xl:col-span-1">
        <div class="kasir-card p-5 sticky top-[88px]">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4"
                 style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-check-circle-fill text-2xl" style="color:#16a34a;"></i>
            </div>

            <h2 class="kasir-section-title">Pembayaran Berhasil</h2>
            <div class="kasir-section-subtitle mb-5">Struk siap dicetak atau ditinjau kembali</div>

            <div class="space-y-3 mb-5 pb-5" style="border-bottom:1px solid rgba(30,58,95,0.06);">
                <div class="flex justify-between gap-4 text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Meja</span>
                    <span class="font-bold" style="color:#1e3a5f;">Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</span>
                </div>
                <div class="flex justify-between gap-4 text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Kasir</span>
                    <span class="font-bold" style="color:#1e3a5f;">{{ $pesanan->user->nama ?? '-' }}</span>
                </div>
                <div class="flex justify-between gap-4 text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Metode</span>
                    <span class="font-bold" style="color:#1e3a5f;">{{ strtoupper($metode) }}</span>
                </div>
                <div class="flex justify-between gap-4 text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Subtotal</span>
                    <span class="font-bold" style="color:#1e3a5f;">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between gap-4 text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Pajak (7%)</span>
                    <span class="font-bold" style="color:#1e3a5f;">Rp{{ number_format($pajak, 0, ',', '.') }}</span>
                </div>
                @if($biayaCard > 0)
                <div class="flex justify-between gap-4 text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Biaya Card (2%)</span>
                    <span class="font-bold" style="color:#1e3a5f;">Rp{{ number_format($biayaCard, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between gap-4 text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Bayar</span>
                    <span class="font-bold" style="color:#1e3a5f;">Rp{{ number_format($pesanan->bayar ?? $totalBayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between gap-4 text-sm pt-2" style="border-top:1px dashed rgba(30,58,95,0.1);">
                    <span style="color:rgba(30,58,95,0.55);">Kembalian</span>
                    <span class="font-black" style="color:#15803d;">Rp{{ number_format($pesanan->kembalian ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('struk.cetak', $pesanan->id_pesanan) }}" target="_blank"
               class="kasir-btn kasir-btn-success w-full mb-2">
                <i class="bi bi-printer"></i>
                <span>Cetak Struk</span>
            </a>
            <a href="{{ $backUrl }}" class="kasir-btn kasir-btn-outline w-full">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Pesanan</span>
            </a>
        </div>
    </div>
</div>

@endsection