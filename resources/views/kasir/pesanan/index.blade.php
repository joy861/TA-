@extends('layouts.kasir')

@section('content')
@php
    $pesanans = $pesanans ?? $dataPesanan ?? $data ?? [];

    if ($pesanans instanceof \Illuminate\Pagination\AbstractPaginator) {
        $pesananItems = collect($pesanans->items());
    } else {
        $pesananItems = collect($pesanans);
    }

    $filter      = request('filter', 'semua');
    $search      = request('search', '');
    $tanggalDari = request('tanggal_dari', '');
    $tanggalSampai = request('tanggal_sampai', '');
    $periodeShortcut = request('periode', ''); // 'hari_ini', 'kemarin', '7_hari'

    // Resolve shortcut ke range tanggal aktual
    $today     = \Carbon\Carbon::today();
    $yesterday = \Carbon\Carbon::yesterday();

    if ($periodeShortcut === 'hari_ini') {
        $filterDari    = $today->copy()->startOfDay();
        $filterSampai  = $today->copy()->endOfDay();
        $tanggalDari   = $filterDari->format('Y-m-d');
        $tanggalSampai = $filterSampai->format('Y-m-d');
    } elseif ($periodeShortcut === 'kemarin') {
        $filterDari    = $yesterday->copy()->startOfDay();
        $filterSampai  = $yesterday->copy()->endOfDay();
        $tanggalDari   = $filterDari->format('Y-m-d');
        $tanggalSampai = $filterSampai->format('Y-m-d');
    } elseif ($periodeShortcut === '7_hari') {
        $filterDari    = $today->copy()->subDays(6)->startOfDay();
        $filterSampai  = $today->copy()->endOfDay();
        $tanggalDari   = $filterDari->format('Y-m-d');
        $tanggalSampai = $filterSampai->format('Y-m-d');
    } else {
        $filterDari   = $tanggalDari   ? \Carbon\Carbon::parse($tanggalDari)->startOfDay()   : null;
        $filterSampai = $tanggalSampai ? \Carbon\Carbon::parse($tanggalSampai)->endOfDay()   : null;
    }

    $isPaidStatus = function ($status) {
        $status = strtolower($status ?? '');
        return in_array($status, ['sudah_bayar', 'sudah bayar', 'lunas', 'selesai']);
    };

$getMeja = function ($item) {
    return $item->meja->nomor_meja
        ?? $item->nomor_meja
        ?? $item->id_meja
        ?? '-';
};

    $getTotal = function ($item) {
        return $item->total_harga ?? $item->total ?? $item->grand_total ?? 0;
    };

    $totalPesanan      = $pesananItems->count();
    $belumBayar        = $pesananItems->filter(fn($item) => !$isPaidStatus($item->status ?? ''))->count();
    $pendapatanHariIni = $pesananItems->filter(fn($item) => $isPaidStatus($item->status ?? ''))->sum(fn($item) => $getTotal($item));

    $pesananFiltered = $pesananItems->filter(function ($item) use ($filter, $search, $isPaidStatus, $getMeja, $filterDari, $filterSampai) {
        $isPaid = $isPaidStatus($item->status ?? '');

        $mejaAsli        = strtolower(trim((string) $getMeja($item)));
        $mejaDenganLabel = 'meja ' . $mejaAsli;
        $keywordAsli     = strtolower(trim((string) $search));
        $keywordBersih   = trim(str_replace(['meja', 'nomor', 'no', '.', '-'], '', $keywordAsli));
        $keywordBersih   = preg_replace('/\s+/', '', $keywordBersih);
        $mejaBersih      = preg_replace('/\s+/', '', $mejaAsli);

        if ($filter === 'belum_bayar' && $isPaid) return false;
        if ($filter === 'sudah_bayar' && !$isPaid) return false;

        if ($keywordAsli !== '') {
            $cocokMeja =
                str_contains($mejaAsli, $keywordAsli) ||
                str_contains($mejaDenganLabel, $keywordAsli) ||
                ($keywordBersih !== '' && str_contains($mejaBersih, $keywordBersih));
            if (!$cocokMeja) return false;
        }

        // ── Filter tanggal ──────────────────────────────────────────────
        if ($filterDari || $filterSampai) {
            $tgl = isset($item->created_at)
                ? \Carbon\Carbon::parse($item->created_at)
                : (isset($item->tanggal) ? \Carbon\Carbon::parse($item->tanggal) : null);

            if (!$tgl) return false;
            if ($filterDari   && $tgl->lt($filterDari))   return false;
            if ($filterSampai && $tgl->gt($filterSampai)) return false;
        }

        return true;
    });

    $inputPesananUrl = \Illuminate\Support\Facades\Route::has('pesanan.create')
        ? route('pesanan.create')
        : url('kasir/pesanan/create');

    // Label periode aktif untuk ditampilkan di UI
    $labelPeriode = '';
    if ($periodeShortcut === 'hari_ini')   $labelPeriode = 'Hari Ini';
    elseif ($periodeShortcut === 'kemarin') $labelPeriode = 'Kemarin';
    elseif ($periodeShortcut === '7_hari') $labelPeriode = '7 Hari Terakhir';
    elseif ($tanggalDari || $tanggalSampai) {
        $labelPeriode = trim(
            ($tanggalDari   ? \Carbon\Carbon::parse($tanggalDari)->format('d M Y')   : '…') .
            ' – ' .
            ($tanggalSampai ? \Carbon\Carbon::parse($tanggalSampai)->format('d M Y') : '…')
        );
    }
@endphp

<style>
    /* Polesan UI Data Pesanan - tidak mengubah logic/filter */
    .pesanan-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .pesanan-stat-card {
        min-height: 104px;
        padding: 16px 18px;
        border-radius: 18px;
    }

    .pesanan-stat-card .kasir-stat-value {
        font-size: 30px;
    }

    .pesanan-stat-card .kasir-stat-note {
        margin-top: 7px;
        font-size: 12px;
    }

    .pesanan-stat-money .kasir-stat-value {
        font-size: 25px !important;
        letter-spacing: -0.7px;
    }

    .pesanan-filter-card {
        padding: 14px;
        margin-bottom: 14px;
    }

    .pesanan-filter-top {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        align-items: center;
    }

    .pesanan-status-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pesanan-search-form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .pesanan-search-input-wrap {
        position: relative;
        width: 260px;
        max-width: 100%;
    }

    .pesanan-search-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(30,58,95,0.38);
        font-size: 12px;
        pointer-events: none;
    }

    .pesanan-search-input {
        width: 100%;
        height: 38px;
        border-radius: 12px;
        border: 1.5px solid rgba(30,58,95,0.12);
        background: #fff;
        color: #1e3a5f;
        padding: 0 12px 0 36px;
        font-size: 13px;
        font-weight: 650;
        outline: none;
        transition: all 0.2s ease;
    }

    .pesanan-search-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.12);
    }

    .pesanan-filter-divider {
        border-top: 1px dashed rgba(30,58,95,0.10);
        margin: 13px 0;
    }

    .pesanan-date-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 9px;
        flex-wrap: wrap;
    }

    .pesanan-date-title {
        font-size: 11px;
        font-weight: 850;
        color: rgba(30,58,95,0.42);
        letter-spacing: 0.10em;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .periode-shortcuts {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
    }

    .btn-periode {
        height: 32px;
        padding: 0 12px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 750;
        border: 1.5px solid rgba(30,58,95,0.14);
        background: #fff;
        color: rgba(30,58,95,0.62);
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .btn-periode:hover {
        border-color: #1e3a5f;
        color: #1e3a5f;
        background: #f0f4ff;
    }

    .btn-periode.active {
        background: #1e3a5f;
        color: #fff;
        border-color: #1e3a5f;
    }

    .date-range-row {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .date-input-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .date-input-wrap i {
        position: absolute;
        left: 10px;
        font-size: 13px;
        color: rgba(30,58,95,0.4);
        pointer-events: none;
    }

    .date-input-wrap input[type="date"] {
        height: 36px;
        padding: 0 10px 0 32px;
        border-radius: 10px;
        border: 1.5px solid rgba(30,58,95,0.14);
        background: #fff;
        color: #1e3a5f;
        font-size: 12px;
        font-weight: 650;
        outline: none;
        cursor: pointer;
        min-width: 142px;
        transition: all 0.2s ease;
    }

    .date-input-wrap input[type="date"]:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.10);
    }

    .periode-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2ff;
        color: #1e3a5f;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .periode-badge a {
        color: rgba(30,58,95,0.42);
        text-decoration: none;
        margin-left: 2px;
    }

    .periode-badge a:hover { color: #1e3a5f; }

    .pesanan-table-card {
        overflow: hidden;
    }

    .pesanan-table-scroll {
        max-height: min(58vh, 520px);
        overflow: auto;
        border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px;
        overscroll-behavior: contain;
    }

    .pesanan-table-scroll .kasir-table {
        margin-bottom: 0;
    }

    .pesanan-table-scroll .kasir-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #ffffff;
        box-shadow: 0 1px 0 rgba(30,58,95,0.08);
    }

    .pesanan-table-scroll::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .pesanan-table-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .pesanan-table-scroll::-webkit-scrollbar-thumb {
        background: rgba(30,58,95,0.18);
        border-radius: 999px;
    }

    .pesanan-table-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(30,58,95,0.28);
    }

    .pesanan-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px;
        border-bottom: 1px solid rgba(30,58,95,0.06);
        flex-wrap: wrap;
    }

    .pesanan-table-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 0 12px;
        border-radius: 999px;
        background: #eef2ff;
        color: #1e3a5f;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .aksi-cell {
        white-space: nowrap;
        text-align: center;
    }

    .aksi-group {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: nowrap;
    }

    .btn-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        height: 32px;
        padding: 0 10px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 800;
        border: none;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .btn-aksi-detail { background: #eef2ff; color: #1e3a5f; }
    .btn-aksi-detail:hover { background: #1e3a5f; color: #fff; }
    .btn-aksi-edit { background: rgba(245,158,11,0.12); color: #b45309; }
    .btn-aksi-edit:hover { background: #b45309; color: #fff; }
    .btn-aksi-bayar { background: #16a34a; color: #fff; }
    .btn-aksi-bayar:hover { background: #15803d; color: #fff; }

    .meja-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }

    .meja-number {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: #1e3a5f;
        color: #60a5fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 900;
        flex-shrink: 0;
    }

    .lunas-note {
        font-size: 11px;
        color: rgba(30,58,95,0.35);
        font-style: italic;
        white-space: nowrap;
    }

    @media (max-width: 1024px) {
        .pesanan-filter-top {
            grid-template-columns: 1fr;
        }

        .pesanan-search-form {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media (max-width: 768px) {
        .pesanan-stats-grid {
            grid-template-columns: 1fr !important;
        }

        .pesanan-search-form {
            flex-wrap: wrap;
        }

        .pesanan-search-input-wrap {
            width: 100%;
        }

        .date-range-row {
            flex-direction: column;
            align-items: stretch;
        }

        .date-input-wrap,
        .date-input-wrap input[type="date"] {
            width: 100%;
        }

        .date-range-row .kasir-btn {
            width: 100%;
        }
    }

    @media (max-width: 1024px) {
        .pesanan-table-scroll {
            max-height: 52vh;
        }
    }

    @media (max-width: 768px) {
        .pesanan-table-scroll {
            max-height: 55vh;
        }
    }

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

{{-- STATS --}}
<div class="pesanan-stats-grid">
    <div class="kasir-stat-card pesanan-stat-card">
        <div class="kasir-stat-label">TOTAL PESANAN</div>
        <div>
            <div class="kasir-stat-value">{{ $totalPesanan }}</div>
            <div class="kasir-stat-note">Transaksi tercatat</div>
        </div>
    </div>

    <div class="kasir-stat-card kasir-stat-accent pesanan-stat-card">
        <div class="kasir-stat-label">BELUM BAYAR</div>
        <div>
            <div class="kasir-stat-value">{{ $belumBayar }}</div>
            <div class="kasir-stat-note">Menunggu pembayaran</div>
        </div>
    </div>

    <div class="kasir-stat-card kasir-stat-primary pesanan-stat-card pesanan-stat-money">
        <div class="kasir-stat-label">PENDAPATAN HARI INI</div>
        <div>
            <div class="kasir-stat-value">
                <span style="font-size:13px;font-weight:750;color:rgba(255,255,255,0.55);margin-right:3px;">Rp</span>{{ number_format((float)$pendapatanHariIni, 0, ',', '.') }}
            </div>
            <div class="kasir-stat-note">Dari pesanan lunas</div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="kasir-card pesanan-filter-card">
    <div class="pesanan-filter-top">
        {{-- Status --}}
        <div class="pesanan-status-tabs">
            <a href="{{ url()->current() }}?filter=semua&periode={{ $periodeShortcut }}&tanggal_dari={{ $tanggalDari }}&tanggal_sampai={{ $tanggalSampai }}&search={{ $search }}"
               class="kasir-btn !h-[36px] !px-4 !text-xs {{ $filter === 'semua' ? 'kasir-btn-primary' : 'kasir-btn-ghost' }}">
                Semua
            </a>
            <a href="{{ url()->current() }}?filter=belum_bayar&periode={{ $periodeShortcut }}&tanggal_dari={{ $tanggalDari }}&tanggal_sampai={{ $tanggalSampai }}&search={{ $search }}"
               class="kasir-btn !h-[36px] !px-4 !text-xs {{ $filter === 'belum_bayar' ? 'kasir-btn-primary' : 'kasir-btn-ghost' }}">
                Belum Bayar
            </a>
            <a href="{{ url()->current() }}?filter=sudah_bayar&periode={{ $periodeShortcut }}&tanggal_dari={{ $tanggalDari }}&tanggal_sampai={{ $tanggalSampai }}&search={{ $search }}"
               class="kasir-btn !h-[36px] !px-4 !text-xs {{ $filter === 'sudah_bayar' ? 'kasir-btn-primary' : 'kasir-btn-ghost' }}">
                Sudah Bayar
            </a>
        </div>

        {{-- Search meja --}}
        <form method="GET" action="{{ url()->current() }}" class="pesanan-search-form">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="hidden" name="periode" value="{{ $periodeShortcut }}">
            <input type="hidden" name="tanggal_dari" value="{{ $tanggalDari }}">
            <input type="hidden" name="tanggal_sampai" value="{{ $tanggalSampai }}">

            <div class="pesanan-search-input-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari meja..."
                       class="pesanan-search-input">
            </div>

            <button type="submit" class="kasir-btn kasir-btn-primary !h-[38px] !text-xs">Cari</button>
            <a href="{{ url()->current() }}" class="kasir-btn kasir-btn-ghost !h-[38px] !text-xs">Reset</a>
        </form>
    </div>

    <div class="pesanan-filter-divider"></div>

    {{-- Date filter --}}
    <div>
        <div class="pesanan-date-head">
            <div class="pesanan-date-title">
                <i class="bi bi-calendar3"></i>
                <span>Filter Tanggal</span>
            </div>

            @if($labelPeriode)
                <span class="periode-badge">
                    <i class="bi bi-funnel-fill" style="font-size:10px;"></i>
                    {{ $labelPeriode }}
                    <a href="{{ url()->current() }}?filter={{ $filter }}&search={{ $search }}" title="Hapus filter tanggal">✕</a>
                </span>
            @endif
        </div>

        <div class="date-range-row">
            <div class="periode-shortcuts">
                <a href="{{ url()->current() }}?filter={{ $filter }}&periode=hari_ini&search={{ $search }}"
                   class="btn-periode {{ $periodeShortcut === 'hari_ini' ? 'active' : '' }}">
                    <i class="bi bi-sun"></i> Hari Ini
                </a>
                <a href="{{ url()->current() }}?filter={{ $filter }}&periode=kemarin&search={{ $search }}"
                   class="btn-periode {{ $periodeShortcut === 'kemarin' ? 'active' : '' }}">
                    <i class="bi bi-arrow-counterclockwise"></i> Kemarin
                </a>
                <a href="{{ url()->current() }}?filter={{ $filter }}&periode=7_hari&search={{ $search }}"
                   class="btn-periode {{ $periodeShortcut === '7_hari' ? 'active' : '' }}">
                    <i class="bi bi-calendar-week"></i> 7 Hari Terakhir
                </a>
            </div>

            <form method="GET" action="{{ url()->current() }}" class="date-range-row">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input type="hidden" name="search" value="{{ $search }}">

                <div class="date-input-wrap">
                    <i class="bi bi-calendar-event"></i>
                    <input type="date" name="tanggal_dari"
                           value="{{ $tanggalDari }}"
                           max="{{ date('Y-m-d') }}"
                           title="Dari tanggal">
                </div>

                <span style="font-size:12px; color:rgba(30,58,95,0.4); font-weight:700;">s/d</span>

                <div class="date-input-wrap">
                    <i class="bi bi-calendar-event"></i>
                    <input type="date" name="tanggal_sampai"
                           value="{{ $tanggalSampai }}"
                           max="{{ date('Y-m-d') }}"
                           title="Sampai tanggal">
                </div>

                <button type="submit" class="kasir-btn kasir-btn-primary !h-[36px] !text-xs">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="kasir-card pesanan-table-card">
    <div class="pesanan-table-header">
        <div>
            <h3 class="kasir-section-title">Daftar Pesanan</h3>
            <p class="kasir-section-subtitle">
                {{ $pesananFiltered->count() }} dari {{ $totalPesanan }} pesanan
                @if($labelPeriode)
                    &nbsp;·&nbsp; <span style="color:#1e3a5f; font-weight:750;">{{ $labelPeriode }}</span>
                @endif
            </p>
        </div>

        <div class="pesanan-table-count">
            {{ $pesananFiltered->count() }} transaksi
        </div>
    </div>

    <div class="kasir-table-wrap pesanan-table-scroll">
        <table class="kasir-table" style="min-width:800px;">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th style="width:160px;">Tanggal</th>
                    <th style="width:140px;">Meja</th>
                    <th style="width:130px;">Total</th>
                    <th style="width:135px;">Status</th>
                    <th style="width:190px; text-align:center;">Aksi</th>
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

                        if ($id) {
                            $detailUrl = \Illuminate\Support\Facades\Route::has('pesanan.detail')
                                ? route('pesanan.detail', $id)
                                : (\Illuminate\Support\Facades\Route::has('pesanan.show')
                                    ? route('pesanan.show', $id)
                                    : url('kasir/pesanan/' . $id));

                            $editUrl = \Illuminate\Support\Facades\Route::has('pesanan.edit')
                                ? route('pesanan.edit', $id)
                                : url('kasir/pesanan/' . $id . '/edit');

                            $bayarUrl = \Illuminate\Support\Facades\Route::has('pesanan.bayar')
                                ? route('pesanan.bayar', $id)
                                : (\Illuminate\Support\Facades\Route::has('transaksi.bayar')
                                    ? route('transaksi.bayar', $id)
                                    : url('kasir/pesanan/' . $id . '/bayar'));
                        } else {
                            $detailUrl = $editUrl = $bayarUrl = '#';
                        }
                    @endphp

                    <tr>
                        <td style="color:rgba(30,58,95,0.35); font-size:12px; text-align:center;">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <span style="font-size:12px; color:rgba(30,58,95,0.65); font-weight:650;">
                                {{ $tanggal }}
                            </span>
                        </td>
                        <td>
                            <div class="meja-pill">
                                <div class="meja-number">{{ $meja }}</div>
                                <span style="font-weight:800; color:#1e3a5f; font-size:13px;">Meja {{ $meja }}</span>
                            </div>
                        </td>
                        <td style="font-weight:950; color:#1e3a5f; font-size:14px;">
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
                                    <span class="lunas-note">Lunas ✓</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="kasir-empty-state">
                                <i class="bi bi-inbox"></i>
                                <div style="font-size:14px; font-weight:800; color:#1e3a5f; margin-bottom:4px;">
                                    Belum ada pesanan
                                </div>
                                <div style="font-size:12px;">
                                    @if($labelPeriode)
                                        Tidak ada pesanan pada periode <strong>{{ $labelPeriode }}</strong>.
                                    @else
                                        Pesanan yang masuk akan muncul di sini.
                                    @endif
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
            {{ $pesanans->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection
