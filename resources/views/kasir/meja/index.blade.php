@extends('layouts.kasir')

@section('content')

<style>
    .meja-card {
        background: #fff;
        border: 1.5px solid rgba(30,58,95,0.08);
        border-radius: 18px;
        padding: 22px 18px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: default;
    }

    .meja-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30,58,95,0.06);
    }

    .meja-card.tersedia {
        border-color: rgba(34,197,94,0.2);
    }

    .meja-card.terisi {
        border-color: rgba(239,68,68,0.2);
    }

    .meja-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 22px;
    }

    .meja-card.tersedia .meja-icon {
        background: rgba(34,197,94,0.1);
        color: #16a34a;
    }

    .meja-card.terisi .meja-icon {
        background: rgba(239,68,68,0.1);
        color: #ef4444;
    }

    .meja-name {
        font-size: 16px;
        font-weight: 900;
        color: #1e3a5f;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
        text-transform: capitalize;
    }

    .meja-cap {
        font-size: 11px;
        color: rgba(30,58,95,0.45);
        font-weight: 600;
        margin-bottom: 12px;
    }

    .meja-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
    }

    .meja-card.tersedia .meja-status-badge {
        background: rgba(34,197,94,0.1);
        color: #15803d;
    }

    .meja-card.terisi .meja-status-badge {
        background: rgba(239,68,68,0.1);
        color: #b91c1c;
    }

    .meja-status-badge::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
</style>

@php
    $tersedia = $meja->where('status', 'kosong')->count();
    $terisi = $meja->where('status', 'terisi')->count();
    $totalMeja = $meja->count();
@endphp

<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">RUANG MAKAN</p>
        <h1 class="kasir-page-title">Status Meja</h1>
        <div class="kasir-page-subtitle">Pantau ketersediaan meja restoran secara real-time</div>
    </div>
</div>

{{-- BENTO STATS --}}
<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:16px;" class="grid-stats-meja">
    <div class="kasir-stat-card">
        <div class="kasir-stat-label">TERSEDIA</div>
        <div>
            <div class="kasir-stat-value" style="color:#16a34a;">{{ $tersedia }}</div>
            <div class="kasir-stat-note">meja siap dipakai</div>
        </div>
    </div>
    <div class="kasir-stat-card kasir-stat-accent">
        <div class="kasir-stat-label">TERISI</div>
        <div>
            <div class="kasir-stat-value">{{ $terisi }}</div>
            <div class="kasir-stat-note">sedang melayani</div>
        </div>
    </div>
    <div class="kasir-stat-card kasir-stat-primary">
        <div class="kasir-stat-label">TOTAL MEJA</div>
        <div>
            <div class="kasir-stat-value">{{ $totalMeja }}</div>
            <div class="kasir-stat-note">terdaftar di sistem</div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .grid-stats-meja { grid-template-columns: 1fr !important; }
    }
</style>

{{-- FILTER --}}
<div class="kasir-card p-4 mb-4">
    <div class="flex flex-wrap gap-2">
        <button class="kasir-btn kasir-btn-primary !h-[38px] !px-4 !text-xs filter-btn active" onclick="filterMeja('semua', this)">
            Semua ({{ $totalMeja }})
        </button>
        <button class="kasir-btn kasir-btn-ghost !h-[38px] !px-4 !text-xs filter-btn" onclick="filterMeja('kosong', this)">
            <span style="width:6px; height:6px; border-radius:50%; background:#16a34a; display:inline-block; margin-right:4px;"></span>
            Tersedia ({{ $tersedia }})
        </button>
        <button class="kasir-btn kasir-btn-ghost !h-[38px] !px-4 !text-xs filter-btn" onclick="filterMeja('terisi', this)">
            <span style="width:6px; height:6px; border-radius:50%; background:#ef4444; display:inline-block; margin-right:4px;"></span>
            Terisi ({{ $terisi }})
        </button>
    </div>
</div>

{{-- GRID --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
    @forelse($meja as $m)
        <div class="meja-card {{ $m->status == 'kosong' ? 'tersedia' : 'terisi' }}"
             data-status="{{ $m->status }}">
            <div class="meja-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <div class="meja-name">
                {{ $m->nama_meja ?? 'Meja '.$m->nomor_meja }}
            </div>
            @if(isset($m->kapasitas))
                <div class="meja-cap">Kapasitas {{ $m->kapasitas }} orang</div>
            @endif
            @if($m->status == 'kosong')
                <span class="meja-status-badge">Tersedia</span>
            @else
                <span class="meja-status-badge">Terisi</span>
            @endif
        </div>
    @empty
        <div class="col-span-full kasir-card p-8 text-center" style="color:rgba(30,58,95,0.4);">
            <i class="bi bi-grid-3x3-gap text-3xl block mb-2"></i>
            <div class="text-sm font-bold" style="color:#1e3a5f;">Belum ada data meja</div>
            <div class="text-xs mt-1">Tambahkan meja melalui panel admin.</div>
        </div>
    @endforelse
</div>

<script>
    function filterMeja(status, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('kasir-btn-primary', 'active');
            b.classList.add('kasir-btn-ghost');
        });
        btn.classList.remove('kasir-btn-ghost');
        btn.classList.add('kasir-btn-primary', 'active');

        document.querySelectorAll('.meja-card').forEach(card => {
            if (status === 'semua') {
                card.style.display = '';
            } else {
                card.style.display = card.dataset.status === status ? '' : 'none';
            }
        });
    }
</script>

@endsection