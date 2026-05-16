@extends('layouts.kasir')

@section('content')
@php
    $pesanans = $pesanans ?? $dataPesanan ?? $data ?? [];

    if ($pesanans instanceof \Illuminate\Pagination\AbstractPaginator) {
        $pesananItems = collect($pesanans->items());
    } else {
        $pesananItems = collect($pesanans);
    }

    $filter = request('filter', 'semua');
    $search = request('search', '');

    $isPaidStatus = function ($status) {
        $status = strtolower($status ?? '');
        return in_array($status, ['sudah_bayar', 'sudah bayar', 'lunas', 'selesai']);
    };

    $getMeja = function ($item) {
        return $item->meja->nomor_meja
            ?? $item->meja->nama_meja
            ?? $item->meja->no_meja
            ?? $item->nomor_meja
            ?? $item->nama_meja
            ?? $item->id_meja
            ?? '-';
    };

    $getTotal = function ($item) {
        return $item->total_harga ?? $item->total ?? $item->grand_total ?? 0;
    };

    $totalPesanan      = $pesananItems->count();
    $belumBayar        = $pesananItems->filter(fn($item) => !$isPaidStatus($item->status ?? ''))->count();
    $pendapatanHariIni = $pesananItems->filter(fn($item) => $isPaidStatus($item->status ?? ''))->sum(fn($item) => $getTotal($item));

    $pesananFiltered = $pesananItems->filter(function ($item) use ($filter, $search, $isPaidStatus, $getMeja) {
        $isPaid = $isPaidStatus($item->status ?? '');

        // Normalisasi pencarian meja.
        // Jadi input seperti "2", "meja 2", "Meja 2", atau "MEJA 2" tetap terbaca sama.
        $mejaAsli = strtolower(trim((string) $getMeja($item)));
        $mejaDenganLabel = 'meja ' . $mejaAsli;

        $keywordAsli = strtolower(trim((string) $search));
        $keywordBersih = trim(str_replace(['meja', 'nomor', 'no', '.', '-'], '', $keywordAsli));
        $keywordBersih = preg_replace('/\s+/', '', $keywordBersih);

        $mejaBersih = preg_replace('/\s+/', '', $mejaAsli);

        if ($filter === 'belum_bayar' && $isPaid) return false;
        if ($filter === 'sudah_bayar' && !$isPaid) return false;

        if ($keywordAsli !== '') {
            $cocokMeja =
                str_contains($mejaAsli, $keywordAsli) ||
                str_contains($mejaDenganLabel, $keywordAsli) ||
                ($keywordBersih !== '' && str_contains($mejaBersih, $keywordBersih));

            if (!$cocokMeja) return false;
        }

        return true;
    });

    $inputPesananUrl = \Illuminate\Support\Facades\Route::has('pesanan.create')
        ? route('pesanan.create')
        : url('kasir/pesanan/create');
@endphp

<style>
    /* Fix button tidak wrap */
    .aksi-cell {
        white-space: nowrap;
        text-align: center;
    }

    .aksi-group {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        flex-wrap: nowrap;
    }

    .btn-aksi {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        height: 30px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .btn-aksi-detail {
        background: #eef2ff;
        color: #1e3a5f;
    }
    .btn-aksi-detail:hover { background: #1e3a5f; color: #fff; }

    .btn-aksi-edit {
        background: rgba(245,158,11,0.12);
        color: #b45309;
    }
    .btn-aksi-edit:hover { background: #b45309; color: #fff; }

    .btn-aksi-bayar {
        background: #16a34a;
        color: #fff;
    }
    .btn-aksi-bayar:hover { background: #15803d; color: #fff; }
</style>

{{-- HEADER --}}
<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">TRANSAKSI</p>
        <h1 class="kasir-page-title">Data Pesanan</h1>
        <div class="kasir-page-subtitle">Kelola pesanan pelanggan, pembayaran, dan detail transaksi</div>
    </div>
    <a href="{{ $inputPesananUrl }}" class="kasir-btn kasir-btn-success">
        <i class="bi bi-plus-circle"></i>
        <span>Input Pesanan</span>
    </a>
</div>

{{-- STATS BENTO --}}
<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:14px;" class="grid-stats">
    <div class="kasir-stat-card">
        <div class="kasir-stat-label">TOTAL PESANAN</div>
        <div>
            <div class="kasir-stat-value">{{ $totalPesanan }}</div>
            <div class="kasir-stat-note">Transaksi tercatat</div>
        </div>
    </div>
    <div class="kasir-stat-card kasir-stat-accent">
        <div class="kasir-stat-label">BELUM BAYAR</div>
        <div>
            <div class="kasir-stat-value">{{ $belumBayar }}</div>
            <div class="kasir-stat-note">Menunggu pembayaran</div>
        </div>
    </div>
    <div class="kasir-stat-card kasir-stat-primary">
        <div class="kasir-stat-label">PENDAPATAN HARI INI</div>
        <div>
            <div class="kasir-stat-value" style="font-size:28px;">
                <span style="font-size:13px;font-weight:700;color:rgba(255,255,255,0.5);margin-right:3px;">Rp</span>{{ number_format((float)$pendapatanHariIni, 0, ',', '.') }}
            </div>
            <div class="kasir-stat-note">Dari pesanan lunas</div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .grid-stats { grid-template-columns: 1fr !important; }
    }
</style>

{{-- FILTER --}}
<div class="kasir-card p-4 mb-4">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div class="flex flex-wrap gap-2">
            <a href="{{ url()->current() }}?filter=semua"
               class="kasir-btn !h-[36px] !px-4 !text-xs {{ $filter === 'semua' ? 'kasir-btn-primary' : 'kasir-btn-ghost' }}">
                Semua
            </a>
            <a href="{{ url()->current() }}?filter=belum_bayar"
               class="kasir-btn !h-[36px] !px-4 !text-xs {{ $filter === 'belum_bayar' ? 'kasir-btn-primary' : 'kasir-btn-ghost' }}">
                Belum Bayar
            </a>
            <a href="{{ url()->current() }}?filter=sudah_bayar"
               class="kasir-btn !h-[36px] !px-4 !text-xs {{ $filter === 'sudah_bayar' ? 'kasir-btn-primary' : 'kasir-btn-ghost' }}">
                Sudah Bayar
            </a>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="flex gap-2">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color:rgba(30,58,95,0.4);"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari meja..."
                       class="h-[36px] w-[200px] sm:w-[240px] rounded-xl pl-9 pr-3 outline-none text-sm"
                       style="border:1.5px solid rgba(30,58,95,0.12); background:#fff; color:#1e3a5f;"
                       onfocus="this.style.borderColor='#60a5fa'"
                       onblur="this.style.borderColor='rgba(30,58,95,0.12)'">
            </div>
            <button type="submit" class="kasir-btn kasir-btn-primary !h-[36px] !text-xs">Cari</button>
            <a href="{{ url()->current() }}" class="kasir-btn kasir-btn-ghost !h-[36px] !text-xs">Reset</a>
        </form>
    </div>
</div>

{{-- TABEL --}}
<div class="kasir-card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
        <div>
            <h3 class="kasir-section-title">Daftar Pesanan</h3>
            <p class="kasir-section-subtitle">{{ $pesananFiltered->count() }} dari {{ $totalPesanan }} pesanan</p>
        </div>
    </div>

    <div class="kasir-table-wrap">
        <table class="kasir-table" style="min-width:820px;">
            <thead>
                <tr>
                    <th style="width:46px;">#</th>
                    <th style="width:160px;">Tanggal</th>
                    <th style="width:130px;">Meja</th>
                    <th style="width:130px;">Total</th>
                    <th style="width:130px;">Status</th>
                    <th style="width:200px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananFiltered as $index => $pesanan)
                    @php
                        $id     = $pesanan->id ?? $pesanan->id_pesanan ?? null;
                        $meja   = $getMeja($pesanan);
                        $total  = $getTotal($pesanan);
                        $status = strtolower($pesanan->status ?? 'pending');
                        $isPaid = $isPaidStatus($status);
                        $tanggal = isset($pesanan->created_at)
                            ? \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y, H:i')
                            : ($pesanan->tanggal ?? '-');

                        // Resolve URLs
                        if ($id) {
                            // Detail
                            if (\Illuminate\Support\Facades\Route::has('pesanan.detail'))
                                $detailUrl = route('pesanan.detail', $id);
                            elseif (\Illuminate\Support\Facades\Route::has('pesanan.show'))
                                $detailUrl = route('pesanan.show', $id);
                            else
                                $detailUrl = url('kasir/pesanan/' . $id);

                            // Edit
                            if (\Illuminate\Support\Facades\Route::has('pesanan.edit'))
                                $editUrl = route('pesanan.edit', $id);
                            else
                                $editUrl = url('kasir/pesanan/' . $id . '/edit');

                            // Bayar
                            if (\Illuminate\Support\Facades\Route::has('pesanan.bayar'))
                                $bayarUrl = route('pesanan.bayar', $id);
                            elseif (\Illuminate\Support\Facades\Route::has('transaksi.bayar'))
                                $bayarUrl = route('transaksi.bayar', $id);
                            else
                                $bayarUrl = url('kasir/pesanan/' . $id . '/bayar');
                        } else {
                            $detailUrl = '#';
                            $editUrl   = '#';
                            $bayarUrl  = '#';
                        }
                    @endphp

                    <tr>
                        <td style="color:rgba(30,58,95,0.35); font-size:12px; text-align:center;">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            <span style="font-size:12px; color:rgba(30,58,95,0.65); font-weight:600;">
                                {{ $tanggal }}
                            </span>
                        </td>

                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:30px; height:30px; border-radius:8px; background:#1e3a5f; color:#60a5fa; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:900; flex-shrink:0;">
                                    {{ $meja }}
                                </div>
                                <span style="font-weight:700; color:#1e3a5f; font-size:13px;">Meja {{ $meja }}</span>
                            </div>
                        </td>

                        <td style="font-weight:900; color:#1e3a5f; font-size:14px;">
                            Rp{{ number_format((float)$total, 0, ',', '.') }}
                        </td>

                        <td>
                            @if($isPaid)
                                <span class="kasir-badge kasir-badge-success">
                                    <span class="dot"></span> Sudah Bayar
                                </span>
                            @else
                                <span class="kasir-badge kasir-badge-warning">
                                    <span class="dot"></span> Belum Bayar
                                </span>
                            @endif
                        </td>

                        {{-- ✅ FIX: tombol tidak wrap, nowrap --}}
                        <td class="aksi-cell">
                            <div class="aksi-group">
                                <a href="{{ $detailUrl }}" class="btn-aksi btn-aksi-detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>

                                @if(!$isPaid)
                                    <a href="{{ $editUrl }}" class="btn-aksi btn-aksi-edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="{{ $bayarUrl }}" class="btn-aksi btn-aksi-bayar">
                                        <i class="bi bi-cash-coin"></i> Bayar
                                    </a>
                                @else
                                    <span style="font-size:11px; color:rgba(30,58,95,0.35); font-style:italic;">
                                        Lunas ✓
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="kasir-empty-state">
                                <i class="bi bi-inbox"></i>
                                <div style="font-size:14px; font-weight:700; color:#1e3a5f; margin-bottom:4px;">
                                    Belum ada pesanan
                                </div>
                                <div style="font-size:12px;">
                                    Pesanan yang masuk akan muncul di sini.
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pesanans instanceof \Illuminate\Pagination\AbstractPaginator)
        <div class="px-5 py-4" style="border-top:1px solid rgba(30,58,95,0.06);">
            {{ $pesanans->links() }}
        </div>
    @endif
</div>

@endsection