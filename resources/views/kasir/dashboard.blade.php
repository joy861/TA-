@extends('layouts.kasir')

@section('content')
@php
    $pesananHariIni = $pesananHariIni ?? $totalPesananHariIni ?? $total_pesanan_hari_ini ?? 0;
    $pesananBelumBayar = $pesananBelumBayar ?? $belumBayar ?? $pesanan_belum_bayar ?? 0;
    $pendapatanHariIni = $pendapatanHariIni ?? $pendapatan_hari_ini ?? 0;
    $pesananTerbaru = collect($pesananTerbaru ?? $transaksiTerbaru ?? $pesanan_terbaru ?? []);
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

    $belumBayarUrl = \Illuminate\Support\Facades\Route::has('pesanan.index')
        ? route('pesanan.index', ['filter' => 'belum_bayar'])
        : url('kasir/pesanan?filter=belum_bayar');

    $statusLunas = ['sudah_bayar', 'sudah bayar', 'lunas', 'selesai'];
@endphp

<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">OVERVIEW</p>
        <h1 class="kasir-page-title">Dashboard Kasir</h1>
        <div class="kasir-page-subtitle">Ringkasan kerja kasir hari ini · {{ $tanggalHariIni }}</div>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ $inputPesananUrl }}" class="kasir-btn kasir-btn-success">
            <i class="bi bi-plus-circle"></i>
            <span>Input Pesanan</span>
        </a>
        <a href="{{ $dataPesananUrl }}" class="kasir-btn kasir-btn-outline">
            <i class="bi bi-receipt"></i>
            <span>Data Pesanan</span>
        </a>
    </div>
</div>

<style>
    .kasir-simple-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .kasir-simple-stat {
        min-height: 138px;
        position: relative;
        overflow: hidden;
    }

    .kasir-simple-stat::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 999px;
        right: -54px;
        top: -54px;
        background: rgba(96, 165, 250, 0.14);
    }

    .kasir-action-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .kasir-action-card {
        min-height: 122px;
    }

    .kasir-action-card:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 900px) {
        .kasir-simple-stats,
        .kasir-action-grid {
            grid-template-columns: 1fr;
        }
    }

    .no-circle-card::before,
.no-circle-card::after {
    display: none !important;
    content: none !important;
}

.no-circle-card {
    background: #ffffff !important;
}
</style>

{{-- STATISTIK UTAMA: dibuat simpel untuk kebutuhan kasir --}}
<div class="kasir-simple-stats">
    <div class="kasir-stat-card kasir-stat-primary kasir-simple-stat">
        <div>
            <div class="kasir-stat-label">PENDAPATAN HARI INI</div>
            <div class="kasir-stat-value" style="font-size:34px;">
                <span style="font-size:14px; font-weight:700; color:rgba(255,255,255,0.5); margin-right:4px;">Rp</span>{{ number_format((float)$pendapatanHariIni, 0, ',', '.') }}
            </div>
            <div class="kasir-stat-note">Dari transaksi yang sudah dibayar</div>
        </div>
    </div>

    <div class="kasir-stat-card kasir-stat-accent kasir-simple-stat">
        <div>
            <div class="kasir-stat-label">TOTAL PESANAN</div>
            <div class="kasir-stat-value">{{ $pesananHariIni }}</div>
            <div class="kasir-stat-note">transaksi hari ini</div>
        </div>
    </div>

    <div class="kasir-stat-card kasir-simple-stat no-circle-card">
    <div>
        <div class="kasir-stat-label">BELUM BAYAR</div>
        <div class="kasir-stat-value" style="color:#b45309;">
            {{ $pesananBelumBayar }}
        </div>
        <div class="kasir-stat-note">pesanan perlu ditindaklanjuti</div>
    </div>
</div>
</div>

{{-- AKSI CEPAT --}}
<div class="kasir-card p-5 mb-5">
    <div class="flex items-start justify-between gap-4 mb-4 flex-wrap">
        <div>
            <h2 class="kasir-section-title">Aksi Cepat</h2>
            <div class="kasir-section-subtitle">Fitur utama yang paling sering dipakai kasir</div>
        </div>
    </div>

    <div class="kasir-action-grid">
        <a href="{{ $inputPesananUrl }}" class="kasir-card-soft kasir-action-card p-4 no-underline block">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#eef2ff;">
                <i class="bi bi-plus-circle text-base" style="color:#1e3a5f;"></i>
            </div>
            <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Input Pesanan</div>
            <div class="text-xs" style="color:rgba(30,58,95,0.5);">Masukkan pesanan baru dari meja pelanggan.</div>
        </a>

        <a href="{{ $dataPesananUrl }}" class="kasir-card-soft kasir-action-card p-4 no-underline block">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#60a5fa;">
                <i class="bi bi-receipt text-base" style="color:#1e3a5f;"></i>
            </div>
            <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Data Pesanan</div>
            <div class="text-xs" style="color:rgba(30,58,95,0.5);">Lihat, edit, detail, dan cetak pesanan dapur.</div>
        </a>

        <a href="{{ $belumBayarUrl }}" class="kasir-card-soft kasir-action-card p-4 no-underline block">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(34,197,94,0.1);">
                <i class="bi bi-cash-coin text-base" style="color:#16a34a;"></i>
            </div>
            <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Pembayaran</div>
            <div class="text-xs" style="color:rgba(30,58,95,0.5);">Buka pesanan belum bayar untuk proses pembayaran.</div>
        </a>
    </div>
</div>

{{-- PESANAN TERBARU --}}
<div class="kasir-card overflow-hidden">
    <div class="flex items-center justify-between gap-4 px-5 py-4 flex-wrap" style="border-bottom:1px solid rgba(30,58,95,0.06);">
        <div>
            <h2 class="kasir-section-title">Pesanan Terbaru</h2>
            <div class="kasir-section-subtitle">Transaksi terakhir yang masuk hari ini</div>
        </div>
        <a href="{{ $dataPesananUrl }}" class="kasir-btn kasir-btn-ghost !h-[36px] !px-3 !text-xs">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    @if($pesananTerbaru->count() > 0)
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
            $meja   = $item->meja->nomor_meja ?? $item->meja->nama_meja ?? $item->meja ?? '-';
            $total  = $item->total_bayar ?? $item->total_harga ?? $item->total ?? 0;
            $status = strtolower($item->status ?? 'pending');
            $waktu  = isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '-';

            $metode = strtolower($item->metode_pembayaran ?? '-');
            $adaBiayaTambahan = in_array($metode, ['card', 'qris']);
        @endphp
        <tr>
            <td style="color:rgba(30,58,95,0.4); font-size:12px;">#{{ $index + 1 }}</td>
            <td class="font-bold">Meja {{ $meja }}</td>
            <td>
                @if($metode !== '-' && $metode !== '')
                    <span class="kasir-badge kasir-badge-info" style="text-transform:uppercase;">
                        {{ $metode }}
                        @if($adaBiayaTambahan)
                            <span style="margin-left:4px; opacity:0.7;">+2%</span>
                        @endif
                    </span>
                @else
                    <span style="color:rgba(30,58,95,0.3); font-size:12px;">-</span>
                @endif
            </td>
            <td class="font-bold">Rp{{ number_format((float)$total, 0, ',', '.') }}</td>
            <td class="text-xs" style="color:rgba(30,58,95,0.5);">{{ $waktu }}</td>
            <td>
                @if(in_array($status, $statusLunas))
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
@endsection
