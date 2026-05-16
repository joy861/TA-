@extends('layouts.kasir')

@section('content')
@php
    $pesananHariIni = $pesananHariIni ?? $totalPesananHariIni ?? $total_pesanan_hari_ini ?? 0;
    $pesananBelumBayar = $pesananBelumBayar ?? $belumBayar ?? $pesanan_belum_bayar ?? 0;
    $pendapatanHariIni = $pendapatanHariIni ?? $pendapatan_hari_ini ?? 0;
    $mejaAktif = $mejaAktif ?? $meja_aktif ?? 0;
    $pesananTerbaru = $pesananTerbaru ?? $transaksiTerbaru ?? $pesanan_terbaru ?? [];
    $tanggalHariIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');

    $inputPesananUrl = \Illuminate\Support\Facades\Route::has('pesanan.create')
        ? route('pesanan.create')
        : (
            \Illuminate\Support\Facades\Route::has('kasir.order')
                ? route('kasir.order')
                : url('kasir/input-pesanan')
        );

    $dataPesananUrl = \Illuminate\Support\Facades\Route::has('pesanan.index')
        ? route('pesanan.index')
        : url('kasir/pesanan');
@endphp

<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">OVERVIEW</p>
        <h1 class="kasir-page-title">Dashboard Kasir</h1>
        <div class="kasir-page-subtitle">Ringkasan aktivitas hari ini · {{ $tanggalHariIni }}</div>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ $inputPesananUrl }}" class="kasir-btn kasir-btn-success">
            <i class="bi bi-plus-circle"></i>
            <span>Input Pesanan</span>
        </a>
        <a href="{{ $dataPesananUrl }}" class="kasir-btn kasir-btn-outline">
            <i class="bi bi-receipt"></i>
            <span>Lihat Pesanan</span>
        </a>
    </div>
</div>

{{-- BENTO STATS --}}
<div style="display:grid; grid-template-columns:2fr 1fr 1fr; grid-template-rows:auto auto; gap:10px; margin-bottom:14px;" class="bento-grid">
    <div class="kasir-stat-card kasir-stat-primary" style="grid-row:1/3;">
        <div>
            <div class="kasir-stat-label">PENDAPATAN HARI INI</div>
            <div class="kasir-stat-value" style="font-size:38px;">
                <span style="font-size:14px; font-weight:700; color:rgba(255,255,255,0.5); margin-right:4px;">Rp</span>{{ number_format((float)$pendapatanHariIni, 0, ',', '.') }}
            </div>
            <div class="kasir-stat-note">Akumulasi transaksi yang sudah dibayar</div>
        </div>
        <div style="display:flex; align-items:center; gap:8px; margin-top:16px;">
            <div style="width:32px; height:32px; border-radius:10px; background:rgba(96,165,250,0.2); display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-graph-up-arrow" style="color:#60a5fa; font-size:14px;"></i>
            </div>
            <div style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.5);">{{ $tanggalHariIni }}</div>
        </div>
    </div>

    <div class="kasir-stat-card kasir-stat-accent">
        <div class="kasir-stat-label">TOTAL PESANAN</div>
        <div>
            <div class="kasir-stat-value">{{ $pesananHariIni }}</div>
            <div class="kasir-stat-note">transaksi hari ini</div>
        </div>
    </div>

    <div class="kasir-stat-card">
        <div class="kasir-stat-label">BELUM BAYAR</div>
        <div>
            <div class="kasir-stat-value" style="color:#b45309;">{{ $pesananBelumBayar }}</div>
            <div class="kasir-stat-note">perlu ditindaklanjuti</div>
        </div>
    </div>

    <div class="kasir-stat-card">
        <div class="kasir-stat-label">MEJA AKTIF</div>
        <div>
            <div class="kasir-stat-value">{{ $mejaAktif }}</div>
            <div class="kasir-stat-note">memiliki pesanan aktif</div>
        </div>
    </div>

    <div class="kasir-stat-card kasir-stat-accent" style="grid-column: span 1;">
        <div class="kasir-stat-label">STATUS</div>
        <div>
            <div style="display:flex; gap:6px; align-items:center; margin-top:6px;">
                <span class="dot" style="width:8px; height:8px; border-radius:50%; background:#16a34a;"></span>
                <span style="font-size:13px; font-weight:800; color:#1e3a5f;">Operasional</span>
            </div>
            <div class="kasir-stat-note">Sistem berjalan normal</div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 1024px) {
        .bento-grid {
            grid-template-columns: 1fr 1fr !important;
            grid-template-rows: auto !important;
        }
        .bento-grid > div:first-child {
            grid-column: span 2;
            grid-row: auto !important;
        }
    }
    @media (max-width: 640px) {
        .bento-grid {
            grid-template-columns: 1fr !important;
        }
        .bento-grid > div:first-child {
            grid-column: span 1;
        }
    }
</style>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
    <div class="xl:col-span-2 space-y-5">

        {{-- AKSI CEPAT --}}
        <div class="kasir-card p-5">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="kasir-section-title">Aksi Cepat</h2>
                    <div class="kasir-section-subtitle">Fitur yang paling sering dipakai kasir</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <a href="{{ $inputPesananUrl }}" class="kasir-card-soft p-4 no-underline block">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#eef2ff;">
                        <i class="bi bi-plus-circle text-base" style="color:#1e3a5f;"></i>
                    </div>
                    <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Input Pesanan</div>
                    <div class="text-xs" style="color:rgba(30,58,95,0.5);">Masukkan pesanan baru dari meja pelanggan.</div>
                </a>

                <a href="{{ $dataPesananUrl }}" class="kasir-card-soft p-4 no-underline block">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#60a5fa;">
                        <i class="bi bi-receipt text-base" style="color:#1e3a5f;"></i>
                    </div>
                    <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Data Pesanan</div>
                    <div class="text-xs" style="color:rgba(30,58,95,0.5);">Lihat pesanan aktif, edit, dan pembayaran.</div>
                </a>

                <div class="kasir-card-soft p-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(34,197,94,0.1);">
                        <i class="bi bi-cash-coin text-base" style="color:#16a34a;"></i>
                    </div>
                    <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Pembayaran</div>
                    <div class="text-xs" style="color:rgba(30,58,95,0.5);">Proses pembayaran cepat dari data pesanan.</div>
                </div>
            </div>
        </div>

        {{-- PESANAN TERBARU --}}
        <div class="kasir-card overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-5 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
                <div>
                    <h2 class="kasir-section-title">Pesanan Terbaru</h2>
                    <div class="kasir-section-subtitle">Transaksi yang baru masuk</div>
                </div>
                <a href="{{ $dataPesananUrl }}" class="kasir-btn kasir-btn-ghost !h-[36px] !px-3 !text-xs">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if(count($pesananTerbaru) > 0)
                <div class="kasir-table-wrap">
                    <table class="kasir-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Meja</th>
                                <th>Total</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesananTerbaru as $index => $item)
                                @php
                                    $meja = $item->meja->nomor_meja ?? $item->meja->nama_meja ?? $item->meja ?? '-';
                                    $total = $item->total_harga ?? $item->total ?? 0;
                                    $status = strtolower($item->status ?? 'pending');
                                    $waktu = isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '-';
                                @endphp
                                <tr>
                                    <td style="color:rgba(30,58,95,0.4); font-size:12px;">#{{ $index + 1 }}</td>
                                    <td class="font-bold">Meja {{ $meja }}</td>
                                    <td class="font-bold">Rp{{ number_format((float)$total, 0, ',', '.') }}</td>
                                    <td class="text-xs" style="color:rgba(30,58,95,0.5);">{{ $waktu }}</td>
                                    <td>
                                        @if(in_array($status, ['sudah_bayar', 'lunas', 'selesai']))
                                            <span class="kasir-badge kasir-badge-success">
                                                <span class="dot"></span> Sudah Bayar
                                            </span>
                                        @else
                                            <span class="kasir-badge kasir-badge-warning">
                                                <span class="dot"></span> Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="kasir-empty-state">
                    <i class="bi bi-inbox"></i>
                    <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Belum ada transaksi</div>
                    <div class="text-xs">Pesanan terbaru akan muncul di sini.</div>
                </div>
            @endif
        </div>
    </div>

    <div class="space-y-5">

        {{-- AKTIVITAS --}}
        <div class="kasir-card p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="kasir-section-title">Aktivitas Hari Ini</h2>
                    <div class="kasir-section-subtitle">{{ $tanggalHariIni }}</div>
                </div>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#eef2ff;">
                    <i class="bi bi-clock-history" style="color:#1e3a5f;"></i>
                </div>
            </div>

            @if(count($pesananTerbaru) > 0)
                <div>
                    @foreach($pesananTerbaru as $item)
                        @php
                            $meja = $item->meja->nomor_meja ?? $item->meja->nama_meja ?? $item->meja ?? '-';
                            $total = $item->total_harga ?? $item->total ?? 0;
                            $status = strtolower($item->status ?? 'pending');
                            $isPaid = in_array($status, ['sudah_bayar', 'lunas', 'selesai']);
                            $waktu = isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '-';
                        @endphp
                        <div class="kasir-mini-list-item py-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 font-black text-xs"
                                 style="background:{{ $isPaid ? '#1e3a5f' : '#eef2ff' }}; color:{{ $isPaid ? '#60a5fa' : '#1e3a5f' }};">
                                M{{ $meja }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold truncate" style="color:#1e3a5f;">Meja {{ $meja }}</div>
                                <div class="text-xs" style="color:rgba(30,58,95,0.45);">{{ $waktu }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold" style="color:#1e3a5f;">Rp{{ number_format((float)$total, 0, ',', '.') }}</div>
                                <div class="text-xs font-semibold" style="color:{{ $isPaid ? '#15803d' : '#b45309' }};">
                                    {{ $isPaid ? 'Lunas' : 'Pending' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="kasir-empty-state" style="padding:32px 16px;">
                    <i class="bi bi-calendar2-check"></i>
                    <div class="text-xs">Belum ada aktivitas hari ini.</div>
                </div>
            @endif
        </div>

        {{-- RINGKASAN CEPAT --}}
        <div class="kasir-card p-5">
            <h2 class="kasir-section-title">Ringkasan Cepat</h2>
            <div class="kasir-section-subtitle mb-4">Status operasional hari ini</div>

            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Pesanan hari ini</span>
                    <span class="font-bold" style="color:#1e3a5f;">{{ $pesananHariIni }} transaksi</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Belum dibayar</span>
                    <span class="font-bold" style="color:#b45309;">{{ $pesananBelumBayar }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span style="color:rgba(30,58,95,0.55);">Meja aktif</span>
                    <span class="font-bold" style="color:#1e3a5f;">{{ $mejaAktif }}</span>
                </div>
                <div class="flex items-center justify-between text-sm pt-3" style="border-top:1px solid rgba(30,58,95,0.06);">
                    <span style="color:rgba(30,58,95,0.55);">Pendapatan</span>
                    <span class="font-black" style="color:#15803d;">Rp{{ number_format((float)$pendapatanHariIni, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection