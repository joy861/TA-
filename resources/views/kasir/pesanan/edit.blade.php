@extends('layouts.kasir')

@section('content')

<style>
    .kasir-form-label {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(30,58,95,0.5);
        margin-bottom: 8px;
        display: block;
    }

    .kasir-select {
        width: 100%;
        height: 46px;
        border-radius: 12px;
        border: 1.5px solid rgba(30,58,95,0.12);
        background: #fff;
        color: #1e3a5f;
        padding: 0 14px;
        font-size: 14px;
        font-weight: 600;
        outline: none;
        transition: all 0.2s ease;
    }

    .kasir-select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.12);
    }

    .kategori-chip {
        height: 32px;
        padding: 0 13px;
        border-radius: 999px;
        border: 1.5px solid rgba(30,58,95,0.12);
        background: #fff;
        color: rgba(30,58,95,0.6);
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .kategori-chip:hover {
        background: #eef2ff;
        border-color: #60a5fa;
        color: #1e3a5f;
    }

    .kategori-chip.active {
        background: #1e3a5f;
        color: #fff;
        border-color: #1e3a5f;
    }

    .kategori-chip.guide-active {
        background: #60a5fa;
        color: #1e3a5f;
        border-color: #60a5fa;
    }

    .search-wrapper { position: relative; }

    .search-input {
        width: 100%;
        height: 38px;
        border-radius: 10px;
        border: 1.5px solid rgba(30,58,95,0.12);
        background: #fff;
        color: #1e3a5f;
        padding: 0 12px 0 36px;
        font-size: 12px;
        font-weight: 600;
        outline: none;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.12);
    }

    .search-input::placeholder { color: rgba(30,58,95,0.3); font-weight: 500; }

    .search-icon {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(30,58,95,0.3);
        font-size: 13px;
        pointer-events: none;
    }

    .menu-card {
        background: #fff;
        border: 1.5px solid rgba(30,58,95,0.1);
        border-radius: 14px;
        padding: 12px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 110px;
    }

    .menu-card:hover { border-color: #60a5fa; transform: translateY(-1px); }
    .menu-card.selected { border-color: #1e3a5f; background: #eef2ff; }

    .menu-card-guide {
        background: #fff;
        border: 1.5px solid rgba(96,165,250,0.2);
        border-radius: 14px;
        padding: 12px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 110px;
    }

    .menu-card-guide:hover { border-color: #60a5fa; transform: translateY(-1px); }
    .menu-card-guide.selected { border-color: #60a5fa; background: #e0f0ff; }

    .check-badge {
        display: none;
        position: absolute;
        top: 8px;
        right: 8px;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        background: #1e3a5f;
        color: #60a5fa;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 900;
    }

    .check-badge-guide {
        display: none;
        position: absolute;
        top: 8px;
        right: 8px;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        background: #60a5fa;
        color: #1e3a5f;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 900;
    }

    .menu-card.selected .check-badge { display: flex; }
    .menu-card-guide.selected .check-badge-guide { display: flex; }

    .menu-nama {
        font-size: 13px;
        font-weight: 800;
        color: #1e3a5f;
        padding-right: 24px;
        line-height: 1.3;
    }

    .menu-harga {
        font-size: 12px;
        color: rgba(30,58,95,0.55);
        font-weight: 700;
        margin-top: 3px;
    }

    .menu-harga-guide {
        font-size: 12px;
        color: #1e5fa5;
        font-weight: 700;
        margin-top: 3px;
    }

    .qty-control {
        display: none;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
    }

    .menu-card.selected .qty-control,
    .menu-card-guide.selected .qty-control { display: flex; }

    .qty-btn {
        width: 26px;
        height: 26px;
        border-radius: 7px;
        border: none;
        background: #1e3a5f;
        color: #60a5fa;
        font-size: 13px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-btn:hover { background: #60a5fa; color: #1e3a5f; }

    .qty-btn-guide {
        width: 26px;
        height: 26px;
        border-radius: 7px;
        border: none;
        background: #60a5fa;
        color: #1e3a5f;
        font-size: 13px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-btn-guide:hover { background: #1e3a5f; color: #60a5fa; }

    .qty-num {
        min-width: 20px;
        text-align: center;
        font-size: 13px;
        font-weight: 900;
        color: #1e3a5f;
    }

    .panel-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 10px;
        margin-bottom: 12px;
        font-size: 12px;
        font-weight: 800;
    }

    .panel-header-normal { background: #1e3a5f; color: #fff; }
    .panel-header-guide  { background: #60a5fa; color: #1e3a5f; }

    .menu-scroll {
        max-height: 480px;
        overflow-y: auto;
        padding-right: 2px;
    }

    .menu-scroll::-webkit-scrollbar { width: 4px; }
    .menu-scroll::-webkit-scrollbar-track { background: transparent; }
    .menu-scroll::-webkit-scrollbar-thumb { background: rgba(30,58,95,0.15); border-radius: 999px; }

    .no-result {
        display: none;
        text-align: center;
        padding: 24px 12px;
        color: rgba(30,58,95,0.3);
        font-size: 12px;
    }

    .order-bar { border-radius: 12px; padding: 12px; }
    .order-bar-normal { background: #eef2ff; }
    .order-bar-guide  { background: #e0f0ff; }

    .order-item {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        padding: 5px 0;
        font-size: 12px;
        color: #1e3a5f;
        border-bottom: 1px solid rgba(30,58,95,0.06);
    }

    .order-item:last-child { border-bottom: none; }

    .order-subtotal {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        border-top: 1.5px solid rgba(30,58,95,0.1);
        margin-top: 8px;
        padding-top: 10px;
        font-size: 13px;
        font-weight: 900;
        color: #1e3a5f;
    }

    .order-grand-total {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        border-top: 2px solid rgba(30,58,95,0.15);
        margin-top: 10px;
        padding-top: 10px;
        font-size: 15px;
        font-weight: 900;
        color: #1e3a5f;
    }

    /* Modal */
    .confirm-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(8,20,43,0.62);
        backdrop-filter: blur(5px);
        display: flex; align-items: center; justify-content: center;
        padding: 20px; z-index: 99999;
        opacity: 0; visibility: hidden;
        transition: all 0.25s ease;
    }

    .confirm-modal-overlay.show { opacity: 1; visibility: visible; }

    .confirm-modal-box {
        width: 100%; max-width: 440px;
        background: #fff; border-radius: 28px;
        padding: 34px 28px 26px; text-align: center;
        box-shadow: 0 30px 80px rgba(8,20,43,0.28);
        border: 1px solid rgba(226,232,240,0.9);
        transform: translateY(20px) scale(0.96);
        transition: all 0.25s ease;
        position: relative; overflow: hidden;
    }

    .confirm-modal-overlay.show .confirm-modal-box { transform: translateY(0) scale(1); }

    .confirm-modal-box::before {
        content: ''; position: absolute;
        width: 180px; height: 180px; border-radius: 999px;
        background: rgba(96,165,250,0.10);
        top: -90px; right: -90px;
    }

    .confirm-modal-icon {
        width: 84px; height: 84px; margin: 0 auto 20px;
        border-radius: 999px; background: #fff7ed;
        border: 4px solid #fed7aa; color: #f59e0b;
        display: flex; align-items: center; justify-content: center;
        font-size: 42px; font-weight: 900;
        position: relative; z-index: 2;
    }

    .confirm-modal-title { font-size: 28px; font-weight: 900; color: #1e3a5f; margin-bottom: 10px; letter-spacing: -0.04em; position: relative; z-index: 2; }
    .confirm-modal-text  { font-size: 16px; font-weight: 700; color: #334155; margin-bottom: 8px; position: relative; z-index: 2; }
    .confirm-modal-subtext { font-size: 14px; color: #7188a7; line-height: 1.6; margin-bottom: 26px; position: relative; z-index: 2; }
    .confirm-modal-actions { display: flex; justify-content: center; gap: 12px; position: relative; z-index: 2; }

    .confirm-btn { border: none; border-radius: 16px; padding: 13px 20px; min-width: 125px; font-size: 14px; font-weight: 800; cursor: pointer; transition: all 0.2s ease; }
    .confirm-btn-cancel { background: #eef2f7; color: #475569; }
    .confirm-btn-cancel:hover { background: #e2e8f0; }
    .confirm-btn-submit { background: linear-gradient(135deg,#1e3a5f,#2563eb); color: #fff; box-shadow: 0 14px 30px rgba(37,99,235,0.24); }
    .confirm-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 18px 35px rgba(37,99,235,0.30); }

    .confirm-success-state { display: none; position: relative; z-index: 2; }
    .confirm-modal-box.success-mode .confirm-normal-state { display: none; }
    .confirm-modal-box.success-mode .confirm-success-state { display: block; }

    .success-check-wrap {
        width: 92px; height: 92px; margin: 0 auto 20px;
        border-radius: 999px; background: #ecfdf5;
        border: 4px solid #bbf7d0;
        display: flex; align-items: center; justify-content: center;
        animation: popSuccess 0.35s ease forwards;
    }

    .success-check {
        width: 42px; height: 22px;
        border-left: 6px solid #16a34a;
        border-bottom: 6px solid #16a34a;
        transform: rotate(-45deg) scale(0);
        animation: drawCheck 0.35s ease 0.18s forwards;
    }

    .success-title { font-size: 28px; font-weight: 900; color: #1e3a5f; margin-bottom: 8px; letter-spacing: -0.04em; }
    .success-text  { font-size: 14px; color: #7188a7; font-weight: 600; }

    @keyframes popSuccess { 0% { transform: scale(0.75); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    @keyframes drawCheck  { 0% { transform: rotate(-45deg) scale(0); opacity: 0; } 100% { transform: rotate(-45deg) scale(1); opacity: 1; } }

    @media (max-width: 576px) {
        .confirm-modal-box { padding: 28px 20px 22px; border-radius: 22px; }
        .confirm-modal-title, .success-title { font-size: 24px; }
        .confirm-modal-actions { flex-direction: column-reverse; }
        .confirm-btn { width: 100%; }
    }
</style>

@php
    $backUrl = \Illuminate\Support\Facades\Route::has('pesanan.index')
        ? route('pesanan.index')
        : url('kasir/pesanan');

    $detailNormal = $pesanan->detailPesanan->filter(function ($d) {
        return ($d->tipe_harga ?? 'normal') !== 'guide';
    });
    $detailGuide = $pesanan->detailPesanan->filter(function ($d) {
        return ($d->tipe_harga ?? '') === 'guide';
    });
@endphp

<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">TRANSAKSI</p>
        <h1 class="kasir-page-title">Edit Pesanan</h1>
        <div class="kasir-page-subtitle">Tambahkan atau hapus menu dari pesanan</div>
    </div>

    <a href="{{ $backUrl }}" class="kasir-btn kasir-btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>
</div>

<form action="{{ route('pesanan.update', $pesanan->id_pesanan) }}" method="POST" id="formUpdatePesanan">
    @csrf
    @method('PUT')

    <div class="space-y-5">

        {{-- PILIH MEJA --}}
        <div class="kasir-card p-5">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="kasir-section-title">Pilih Meja</h2>
                    <div class="kasir-section-subtitle">Meja pesanan yang sedang diedit</div>
                </div>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#eef2ff;">
                    <i class="bi bi-grid-3x3-gap" style="color:#1e3a5f;"></i>
                </div>
            </div>
            <label class="kasir-form-label">Nomor Meja</label>
            <select name="id_meja" class="kasir-select" required>
                @foreach($meja as $m)
                    <option value="{{ $m->id_meja }}" {{ $pesanan->id_meja == $m->id_meja ? 'selected' : '' }}>
                        Meja {{ $m->nomor_meja }} — {{ $m->kapasitas }} orang
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 2 KOLOM MENU + RINGKASAN --}}
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

            {{-- KIRI: HARGA NORMAL --}}
            <div class="xl:col-span-2 kasir-card p-4">
                <div class="panel-header panel-header-normal">
                    <i class="bi bi-person"></i>
                    <span>Menu Customer (Harga Normal)</span>
                </div>

                <div class="search-wrapper mb-3">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Cari menu..."
                           oninput="filterGrid('normal', this.value)">
                </div>

                <div class="flex flex-wrap gap-1.5 mb-3">
                    <button type="button" class="kategori-chip active"
                            onclick="filterKategori('normal', 'semua', this)">Semua</button>
                    @foreach($kategori as $kat)
                        <button type="button" class="kategori-chip"
                                onclick="filterKategori('normal', '{{ (int) $kat->id_kategori }}', this)">
                            {{ $kat->nama_kategori }}
                        </button>
                    @endforeach
                </div>

                <div class="menu-scroll">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-2" id="grid-normal">
                        @foreach($menu as $mn)
                            @php
                                $detailN = $detailNormal->first(function ($d) use ($mn) {
                                    return $d->id_menu == $mn->id_menu;
                                });
                                $isSelN = $detailN !== null;
                                $qtyN   = $detailN ? $detailN->jumlah : 1;
                            @endphp
                            <div class="menu-card {{ $isSelN ? 'selected' : '' }}"
                                 data-grid="normal"
                                 data-id="{{ $mn->id_menu }}"
                                 data-kategori="{{ $mn->id_kategori }}"
                                 data-harga="{{ $mn->harga }}"
                                 data-nama="{{ $mn->nama_menu }}"
                                 onclick="toggleMenu('normal', this)">
                                <div class="check-badge"><i class="bi bi-check-lg"></i></div>
                                <div>
                                    <div class="menu-nama">{{ $mn->nama_menu }}</div>
                                    <div class="menu-harga">Rp{{ number_format($mn->harga, 0, ',', '.') }}</div>
                                </div>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" onclick="ubahQty(event, this, -1, 'normal')">−</button>
                                    <span class="qty-num">{{ $qtyN }}</span>
                                    <button type="button" class="qty-btn" onclick="ubahQty(event, this, 1, 'normal')">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="no-result" id="no-result-normal">
                        <i class="bi bi-search" style="font-size:20px; display:block; margin-bottom:6px;"></i>
                        Menu tidak ditemukan
                    </div>
                </div>
            </div>

            {{-- KANAN: HARGA GUIDE --}}
            <div class="xl:col-span-2 kasir-card p-4">
                <div class="panel-header panel-header-guide">
                    <i class="bi bi-star-fill"></i>
                    <span>Menu Guide (Harga Guide)</span>
                </div>

                <div class="search-wrapper mb-3">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Cari menu..."
                           oninput="filterGrid('guide', this.value)">
                </div>

                <div class="flex flex-wrap gap-1.5 mb-3">
                    <button type="button" class="kategori-chip guide-active"
                            onclick="filterKategori('guide', 'semua', this)">Semua</button>
                    @foreach($kategori as $kat)
                        <button type="button" class="kategori-chip"
                                onclick="filterKategori('guide', '{{ (int) $kat->id_kategori }}', this)">
                            {{ $kat->nama_kategori }}
                        </button>
                    @endforeach
                </div>

                <div class="menu-scroll">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-2" id="grid-guide">
                        @foreach($menu as $mn)
                            @php
                                $detailG = $detailGuide->first(function ($d) use ($mn) {
                                    return $d->id_menu == $mn->id_menu;
                                });
                                $isSelG = $detailG !== null;
                                $qtyG   = $detailG ? $detailG->jumlah : 1;
                            @endphp
                            <div class="menu-card-guide {{ $isSelG ? 'selected' : '' }}"
                                 data-grid="guide"
                                 data-id="{{ $mn->id_menu }}"
                                 data-kategori="{{ $mn->id_kategori }}"
                                 data-harga="{{ $mn->harga_guide ?? $mn->harga }}"
                                 data-nama="{{ $mn->nama_menu }}"
                                 onclick="toggleMenu('guide', this)">
                                <div class="check-badge-guide"><i class="bi bi-check-lg"></i></div>
                                <div>
                                    <div class="menu-nama">{{ $mn->nama_menu }}</div>
                                    <div class="menu-harga-guide">Rp{{ number_format($mn->harga_guide ?? $mn->harga, 0, ',', '.') }}</div>
                                </div>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn-guide" onclick="ubahQty(event, this, -1, 'guide')">−</button>
                                    <span class="qty-num">{{ $qtyG }}</span>
                                    <button type="button" class="qty-btn-guide" onclick="ubahQty(event, this, 1, 'guide')">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="no-result" id="no-result-guide">
                        <i class="bi bi-search" style="font-size:20px; display:block; margin-bottom:6px;"></i>
                        Menu tidak ditemukan
                    </div>
                </div>
            </div>

            {{-- RINGKASAN --}}
            <div class="xl:col-span-1">
                <div class="kasir-card p-4 sticky top-[88px]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="kasir-section-title">Ringkasan</h2>
                            <div class="kasir-section-subtitle">Total pesanan saat ini</div>
                        </div>
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(34,197,94,0.1);">
                            <i class="bi bi-basket" style="color:#16a34a;"></i>
                        </div>
                    </div>

                    <div id="order-empty" class="kasir-empty-state" style="padding:20px 12px; display:none;">
                        <i class="bi bi-cart"></i>
                        <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Belum ada menu</div>
                        <div class="text-xs">Pilih dari kolom kiri atau kanan.</div>
                    </div>

                    <div id="summary-normal" style="display:none;">
                        <div class="order-bar order-bar-normal mb-3">
                            <div class="kasir-form-label" style="color:#1e3a5f;">
                                <i class="bi bi-person"></i> Customer
                            </div>
                            <div id="list-normal"></div>
                            <div class="order-subtotal">
                                <span>Subtotal</span>
                                <span id="subtotal-normal">Rp0</span>
                            </div>
                        </div>
                    </div>

                    <div id="summary-guide" style="display:none;">
                        <div class="order-bar order-bar-guide mb-3">
                            <div class="kasir-form-label" style="color:#1e5fa5;">
                                <i class="bi bi-star-fill"></i> Guide
                            </div>
                            <div id="list-guide"></div>
                            <div class="order-subtotal" style="color:#1e5fa5;">
                                <span>Subtotal</span>
                                <span id="subtotal-guide">Rp0</span>
                            </div>
                        </div>
                    </div>

                    <div id="grand-total-wrap" style="display:none;">
                        <div class="order-grand-total">
                            <span>Total</span>
                            <span id="grand-total">Rp0</span>
                        </div>
                    </div>

                    <button type="button" class="kasir-btn kasir-btn-primary w-full mt-4"
                            onclick="openConfirmUpdateModal()">
                        <i class="bi bi-save"></i>
                        <span>Update Pesanan</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div id="hidden-inputs"></div>
</form>

{{-- MODAL KONFIRMASI UPDATE --}}
<div id="confirmUpdateModal" class="confirm-modal-overlay">
    <div id="confirmUpdateBox" class="confirm-modal-box">
        <div class="confirm-normal-state">
            <div class="confirm-modal-icon">!</div>
            <h3 class="confirm-modal-title">Konfirmasi Pesanan</h3>
            <p class="confirm-modal-text">Yakin ingin menyimpan perubahan?</p>
            <p class="confirm-modal-subtext">Data pesanan akan diperbarui sesuai menu yang dipilih saat ini.</p>
            <div class="confirm-modal-actions">
                <button type="button" class="confirm-btn confirm-btn-cancel" onclick="closeConfirmUpdateModal()">Batal</button>
                <button type="button" class="confirm-btn confirm-btn-submit" onclick="confirmSubmitUpdatePesanan()">Ya, Update</button>
            </div>
        </div>
        <div class="confirm-success-state">
            <div class="success-check-wrap"><div class="success-check"></div></div>
            <h3 class="success-title">Berhasil</h3>
            <p class="success-text">Pesanan sedang diperbarui...</p>
        </div>
    </div>
</div>

<script>
    const pesananNormal  = {};
    const pesananGuide   = {};
    const activeKategori = { normal: 'semua', guide: 'semua' };
    const activeSearch   = { normal: '', guide: '' };

    // Inisialisasi dari card yang sudah selected
    document.querySelectorAll('#grid-normal .menu-card.selected').forEach(card => {
        pesananNormal[card.dataset.id] = parseInt(card.querySelector('.qty-num').textContent);
    });

    document.querySelectorAll('#grid-guide .menu-card-guide.selected').forEach(card => {
        pesananGuide[card.dataset.id] = parseInt(card.querySelector('.qty-num').textContent);
    });

    // Inject id_detail[] untuk detail yang sudah ada
    @foreach($pesanan->detailPesanan as $d)
        (function() {
            const wrap = document.getElementById('hidden-inputs');
            const hid  = document.createElement('input');
            hid.type   = 'hidden';
            hid.name   = 'id_detail[]';
            hid.value  = '{{ $d->id_detail }}';
            hid.dataset.forMenu   = '{{ $d->id_menu }}';
            hid.dataset.tipeHarga = '{{ $d->tipe_harga ?? "normal" }}';
            wrap.appendChild(hid);
        })();
    @endforeach

    updateHidden();
    updateOrderBar();

    function toggleMenu(grid, card) {
        const id      = card.dataset.id;
        const pesanan = grid === 'normal' ? pesananNormal : pesananGuide;

        if (pesanan[id]) {
            delete pesanan[id];
            card.classList.remove('selected');
            card.querySelector('.qty-num').textContent = 1;
        } else {
            pesanan[id] = 1;
            card.classList.add('selected');
        }

        updateHidden();
        updateOrderBar();
    }

    function ubahQty(event, btn, delta, grid) {
        event.stopPropagation();
        const card    = btn.closest(grid === 'normal' ? '.menu-card' : '.menu-card-guide');
        const id      = card.dataset.id;
        const pesanan = grid === 'normal' ? pesananNormal : pesananGuide;
        const qtyEl   = card.querySelector('.qty-num');
        let qty = (pesanan[id] || 0) + delta;

        if (qty < 1) {
            delete pesanan[id];
            card.classList.remove('selected');
            qtyEl.textContent = 1;
        } else {
            pesanan[id] = qty;
            qtyEl.textContent = qty;
        }

        updateHidden();
        updateOrderBar();
    }

    function updateHidden() {
        const wrap = document.getElementById('hidden-inputs');

        wrap.querySelectorAll(
            'input[name="menu[]"], input[name="jumlah[]"], input[name="harga_pakai[]"], input[name="tipe_harga[]"]'
        ).forEach(el => el.remove());

        Object.entries(pesananNormal).forEach(([id, qty]) => {
            const card = document.querySelector(`#grid-normal .menu-card[data-id="${id}"]`);
            appendHidden(wrap, 'menu[]',        id);
            appendHidden(wrap, 'jumlah[]',      qty);
            appendHidden(wrap, 'harga_pakai[]', card.dataset.harga);
            appendHidden(wrap, 'tipe_harga[]',  'normal');
        });

        Object.entries(pesananGuide).forEach(([id, qty]) => {
            const card = document.querySelector(`#grid-guide .menu-card-guide[data-id="${id}"]`);
            appendHidden(wrap, 'menu[]',        id);
            appendHidden(wrap, 'jumlah[]',      qty);
            appendHidden(wrap, 'harga_pakai[]', card.dataset.harga);
            appendHidden(wrap, 'tipe_harga[]',  'guide');
        });
    }

    function appendHidden(wrap, name, value) {
        const el = document.createElement('input');
        el.type  = 'hidden';
        el.name  = name;
        el.value = value;
        wrap.appendChild(el);
    }

    function updateOrderBar() {
        const hasNormal = Object.keys(pesananNormal).length > 0;
        const hasGuide  = Object.keys(pesananGuide).length > 0;

        document.getElementById('order-empty').style.display      = (!hasNormal && !hasGuide) ? 'block' : 'none';
        document.getElementById('summary-normal').style.display   = hasNormal ? 'block' : 'none';
        document.getElementById('summary-guide').style.display    = hasGuide  ? 'block' : 'none';
        document.getElementById('grand-total-wrap').style.display = (hasNormal || hasGuide) ? 'block' : 'none';

        let totalNormal = 0;
        let totalGuide  = 0;

        document.getElementById('list-normal').innerHTML = Object.entries(pesananNormal).map(([id, qty]) => {
            const card  = document.querySelector(`#grid-normal .menu-card[data-id="${id}"]`);
            const harga = parseInt(card.dataset.harga);
            const sub   = harga * qty;
            totalNormal += sub;
            return `<div class="order-item">
                <span style="font-weight:600;">${card.dataset.nama} <strong>x${qty}</strong></span>
                <span style="font-weight:800;white-space:nowrap;">Rp${sub.toLocaleString('id-ID')}</span>
            </div>`;
        }).join('');
        document.getElementById('subtotal-normal').textContent = 'Rp' + totalNormal.toLocaleString('id-ID');

        document.getElementById('list-guide').innerHTML = Object.entries(pesananGuide).map(([id, qty]) => {
            const card  = document.querySelector(`#grid-guide .menu-card-guide[data-id="${id}"]`);
            const harga = parseInt(card.dataset.harga);
            const sub   = harga * qty;
            totalGuide += sub;
            return `<div class="order-item">
                <span style="font-weight:600;">${card.dataset.nama} <strong>x${qty}</strong></span>
                <span style="font-weight:800;white-space:nowrap;">Rp${sub.toLocaleString('id-ID')}</span>
            </div>`;
        }).join('');
        document.getElementById('subtotal-guide').textContent = 'Rp' + totalGuide.toLocaleString('id-ID');

        document.getElementById('grand-total').textContent = 'Rp' + (totalNormal + totalGuide).toLocaleString('id-ID');
    }

    function filterKategori(grid, kategori, btn) {
        activeKategori[grid] = kategori;
        btn.closest('.kasir-card').querySelectorAll('.kategori-chip').forEach(b => {
            b.classList.remove('active', 'guide-active');
        });
        btn.classList.add(grid === 'guide' ? 'guide-active' : 'active');
        applyFilter(grid);
    }

    function filterGrid(grid, value) {
        activeSearch[grid] = value.toLowerCase().trim();
        applyFilter(grid);
    }

    function applyFilter(grid) {
        const selector = grid === 'normal' ? '#grid-normal .menu-card' : '#grid-guide .menu-card-guide';
        const noResult = document.getElementById(`no-result-${grid}`);
        const kat      = activeKategori[grid];
        const keyword  = activeSearch[grid];
        let visible    = 0;

        document.querySelectorAll(selector).forEach(card => {
            const show = (kat === 'semua' || card.dataset.kategori == kat)
                      && card.dataset.nama.toLowerCase().includes(keyword);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        noResult.style.display = visible === 0 ? 'block' : 'none';
    }

    function openConfirmUpdateModal() {
        const form = document.getElementById('formUpdatePesanan');
        if (!form.checkValidity()) { form.reportValidity(); return; }
        document.getElementById('confirmUpdateBox').classList.remove('success-mode');
        document.getElementById('confirmUpdateModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmUpdateModal() {
        document.getElementById('confirmUpdateModal').classList.remove('show');
        document.getElementById('confirmUpdateBox').classList.remove('success-mode');
        document.body.style.overflow = '';
    }

    function confirmSubmitUpdatePesanan() {
        document.getElementById('confirmUpdateBox').classList.add('success-mode');
        setTimeout(() => document.getElementById('formUpdatePesanan').submit(), 900);
    }

    document.getElementById('confirmUpdateModal').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmUpdateModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeConfirmUpdateModal();
    });
</script>

@endsection