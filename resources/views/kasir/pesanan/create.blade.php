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

    .meja-card-clean {
        padding: 18px !important;
    }

    .meja-select-wrapper {
        position: relative;
    }

    .meja-select-wrapper::before {
        content: '\F3E8';
        font-family: "bootstrap-icons";
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        border-radius: 9px;
        background: #eef2ff;
        color: #1e3a5f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        z-index: 1;
        pointer-events: none;
    }

    .meja-select-wrapper .kasir-select {
        height: 50px;
        padding-left: 54px;
        border-radius: 14px;
        border-color: rgba(30,58,95,0.14);
        box-shadow: 0 8px 22px rgba(30,58,95,0.03);
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

    .kategori-scroll {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        padding: 0 2px 6px 2px;
        margin-bottom: 12px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }

    .kategori-scroll::-webkit-scrollbar {
        height: 4px;
    }

    .kategori-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .kategori-scroll::-webkit-scrollbar-thumb {
        background: rgba(30,58,95,0.14);
        border-radius: 999px;
    }


    .search-wrapper { position: relative; }

    .search-input {
        width: 100%;
        height: 40px;
        border-radius: 12px;
        border: 1.5px solid rgba(30,58,95,0.12);
        background: #fff;
        color: #1e3a5f;
        padding: 0 12px 0 38px;
        font-size: 12px;
        font-weight: 600;
        outline: none;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.12);
    }

    .search-input.guide-search:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }

    .search-input::placeholder { color: rgba(30,58,95,0.3); font-weight: 500; }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(30,58,95,0.3);
        font-size: 13px;
        pointer-events: none;
    }

    .input-order-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 18px;
        align-items: start;
    }

    .menu-workspace {
        min-width: 0;
    }

    .summary-column {
        min-width: 0;
    }

    .summary-sticky {
        position: sticky;
        top: 88px;
    }

    .menu-tabs {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 14px;
        padding: 6px;
        background: #eef2ff;
        border-radius: 16px;
        position: relative;
    }

    .menu-tabs::before {
        content: '';
        position: absolute;
        top: 12px;
        bottom: 12px;
        left: 50%;
        width: 1px;
        background: rgba(30,58,95,0.12);
        transform: translateX(-50%);
        z-index: 0;
    }

    .menu-tab-btn {
        border: none;
        border-radius: 12px;
        min-height: 42px;
        position: relative;
        z-index: 1;
        padding: 8px 10px;
        font-size: 12px;
        font-weight: 900;
        color: rgba(30,58,95,0.62);
        background: transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .menu-tab-btn.active {
        background: #1e3a5f;
        color: #ffffff;
        box-shadow: 0 10px 24px rgba(30,58,95,0.16);
    }

    .menu-tab-btn.guide-tab.active {
        background: #60a5fa;
        color: #1e3a5f;
    }

    .menu-panel { display: none; }
    .menu-panel.active { display: block; }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 14px;
        margin-bottom: 12px;
    }

    .panel-header-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 900;
    }

    .panel-header-note {
        font-size: 11px;
        font-weight: 800;
        opacity: 0.7;
        white-space: nowrap;
    }

    .panel-header-normal { background: #1e3a5f; color: #fff; }
    .panel-header-guide  { background: #60a5fa; color: #1e3a5f; }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
    }

    .menu-card,
    .menu-card-guide {
        background: #fff;
        border-radius: 15px;
        padding: 12px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 94px;
    }

    .menu-card {
        border: 1.5px solid rgba(30,58,95,0.1);
    }

    .menu-card-guide {
        border: 1.5px solid rgba(96,165,250,0.24);
    }

    .menu-card:hover,
    .menu-card-guide:hover {
        border-color: #60a5fa;
        transform: translateY(-1px);
        box-shadow: 0 10px 26px rgba(30,58,95,0.06);
    }

    .menu-card.selected { border-color: #1e3a5f; background: #eef2ff; }
    .menu-card-guide.selected { border-color: #60a5fa; background: #e0f0ff; }
    .menu-card.habis,
    .menu-card-guide.habis { opacity: 0.4; pointer-events: none; }

    .check-badge,
    .check-badge-guide {
        display: none;
        position: absolute;
        top: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        border-radius: 999px;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 900;
    }

    .check-badge { background: #1e3a5f; color: #60a5fa; }
    .check-badge-guide { background: #60a5fa; color: #1e3a5f; }
    .menu-card.selected .check-badge { display: flex; }
    .menu-card-guide.selected .check-badge-guide { display: flex; }

    .menu-nama {
        font-size: 13px;
        font-weight: 900;
        color: #1e3a5f;
        padding-right: 26px;
        line-height: 1.3;
    }

    .menu-harga,
    .menu-harga-guide {
        font-size: 12px;
        font-weight: 800;
        margin-top: 4px;
    }

    .menu-harga { color: rgba(30,58,95,0.58); }
    .menu-harga-guide { color: #1e5fa5; }

    .qty-control {
        display: none;
        align-items: center;
        gap: 7px;
        margin-top: 9px;
    }

    .menu-card.selected .qty-control,
    .menu-card-guide.selected .qty-control { display: flex; }

    .qty-btn,
    .qty-btn-guide {
        width: 28px;
        height: 28px;
        border-radius: 9px;
        border: none;
        font-size: 14px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-btn { background: #1e3a5f; color: #60a5fa; }
    .qty-btn:hover { background: #60a5fa; color: #1e3a5f; }
    .qty-btn-guide { background: #60a5fa; color: #1e3a5f; }
    .qty-btn-guide:hover { background: #1e3a5f; color: #60a5fa; }

    .qty-num {
        min-width: 22px;
        text-align: center;
        font-size: 13px;
        font-weight: 900;
        color: #1e3a5f;
    }

    .order-bar {
        border-radius: 14px;
        padding: 12px;
    }

    .order-bar-normal { background: #eef2ff; }
    .order-bar-guide  { background: #e0f0ff; }

    .order-item {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        padding: 7px 0;
        font-size: 12px;
        color: #1e3a5f;
        border-bottom: 1px solid rgba(30,58,95,0.06);
    }

    .order-item:last-child { border-bottom: none; }

    .order-subtotal,
    .order-grand-total {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        color: #1e3a5f;
    }

    .order-subtotal {
        border-top: 1.5px solid rgba(30,58,95,0.1);
        margin-top: 8px;
        padding-top: 10px;
        font-size: 13px;
        font-weight: 900;
    }

    .order-grand-total {
        border-top: 2px solid rgba(30,58,95,0.15);
        margin-top: 10px;
        padding-top: 12px;
        font-size: 16px;
        font-weight: 900;
    }

    .no-result {
        display: none;
        text-align: center;
        padding: 26px 12px;
        color: rgba(30,58,95,0.34);
        font-size: 12px;
    }

    .menu-scroll {
        height: min(54vh, 470px);
        min-height: 330px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .menu-scroll::-webkit-scrollbar { width: 5px; }
    .menu-scroll::-webkit-scrollbar-track { background: transparent; }
    .menu-scroll::-webkit-scrollbar-thumb { background: rgba(30,58,95,0.16); border-radius: 999px; }

    .summary-list-scroll {
        max-height: 42vh;
        overflow-y: auto;
        padding-right: 2px;
    }

    .summary-list-scroll::-webkit-scrollbar { width: 4px; }
    .summary-list-scroll::-webkit-scrollbar-track { background: transparent; }
    .summary-list-scroll::-webkit-scrollbar-thumb { background: rgba(30,58,95,0.16); border-radius: 999px; }

    @media (max-width: 1100px) {
        .input-order-layout {
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 14px;
        }

    }

    @media (max-width: 767px) {
        .input-order-layout {
            grid-template-columns: 1fr;
        }

        .summary-sticky {
            position: static;
        }


        .menu-scroll {
            height: 420px;
            min-height: 300px;
        }
    }
</style>

@php
    $backUrl = \Illuminate\Support\Facades\Route::has('pesanan.index')
        ? route('pesanan.index')
        : url('kasir/pesanan');
@endphp

<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">TRANSAKSI</p>
        <h1 class="kasir-page-title">Input Pesanan</h1>
        <div class="kasir-page-subtitle">Pilih meja, lalu tambahkan menu dari tab Customer atau Guide</div>
    </div>
    <a href="{{ $backUrl }}" class="kasir-btn kasir-btn-outline">
        <i class="bi bi-receipt"></i>
        <span>Data Pesanan</span>
    </a>
</div>

<form action="{{ route('pesanan.store') }}" method="POST" id="form-pesanan">
    @csrf

    <div class="space-y-5">
        <div class="kasir-card meja-card-clean">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="kasir-section-title">Pilih Meja</h2>
                    <div class="kasir-section-subtitle">Tentukan meja pelanggan yang sedang memesan</div>
                </div>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#eef2ff;">
                    <i class="bi bi-grid-3x3-gap" style="color:#1e3a5f;"></i>
                </div>
            </div>
            <div class="meja-select-wrapper">
                <select name="id_meja" class="kasir-select" required>
                    <option value="">-- Pilih Meja --</option>
                    @foreach($meja as $m)
                        <option value="{{ $m->id_meja }}">
                            Meja {{ $m->nomor_meja }} — {{ $m->kapasitas }} orang
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="input-order-layout">
            <div class="menu-workspace kasir-card p-4">
                <div class="menu-tabs">
                    <button type="button" class="menu-tab-btn active" data-menu-tab="normal" onclick="switchMenuTab('normal')">
                        <i class="bi bi-person"></i>
                        Customer
                    </button>
                    <button type="button" class="menu-tab-btn guide-tab" data-menu-tab="guide" onclick="switchMenuTab('guide')">
                        <i class="bi bi-star-fill"></i>
                        Guide
                    </button>
                </div>

                <div id="panel-normal" class="menu-panel active">
                    <div class="panel-header panel-header-normal">
                        <div class="panel-header-title">
                            <i class="bi bi-person"></i>
                            <span>Menu Customer</span>
                        </div>
                        <div class="panel-header-note">Harga Normal</div>
                    </div>

                    <div class="search-wrapper mb-3">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Cari menu customer..." oninput="filterGrid('normal', this.value)">
                    </div>

                    <div class="kategori-scroll">
                        <button type="button" class="kategori-chip active" onclick="filterKategori('normal', 'semua', this)">Semua</button>
                        @foreach($kategori as $kat)
                            <button type="button" class="kategori-chip" onclick="filterKategori('normal', '{{ (int) $kat->id_kategori }}', this)">
                                {{ $kat->nama_kategori }}
                            </button>
                        @endforeach
                    </div>

                    <div class="menu-scroll">
                        <div class="menu-grid" id="grid-normal">
                            @foreach($menu as $mn)
                                @if($mn->status !== 'habis')
                                    <div class="menu-card"
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
                                            <span class="qty-num">1</span>
                                            <button type="button" class="qty-btn" onclick="ubahQty(event, this, 1, 'normal')">+</button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="no-result" id="no-result-normal">
                            <i class="bi bi-search" style="font-size:20px; display:block; margin-bottom:6px;"></i>
                            Menu tidak ditemukan
                        </div>
                    </div>
                </div>

                <div id="panel-guide" class="menu-panel">
                    <div class="panel-header panel-header-guide">
                        <div class="panel-header-title">
                            <i class="bi bi-star-fill"></i>
                            <span>Menu Guide</span>
                        </div>
                        <div class="panel-header-note">Harga Guide</div>
                    </div>

                    <div class="search-wrapper mb-3">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="search-input guide-search" placeholder="Cari menu guide..." oninput="filterGrid('guide', this.value)">
                    </div>

                    <div class="kategori-scroll">
                        <button type="button" class="kategori-chip guide-active" onclick="filterKategori('guide', 'semua', this)">Semua</button>
                        @foreach($kategori as $kat)
                            <button type="button" class="kategori-chip" onclick="filterKategori('guide', '{{ (int) $kat->id_kategori }}', this)">
                                {{ $kat->nama_kategori }}
                            </button>
                        @endforeach
                    </div>

                    <div class="menu-scroll">
                        <div class="menu-grid" id="grid-guide">
                            @foreach($menu as $mn)
                                @if($mn->status !== 'habis')
                                    <div class="menu-card-guide"
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
                                            <span class="qty-num">1</span>
                                            <button type="button" class="qty-btn-guide" onclick="ubahQty(event, this, 1, 'guide')">+</button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="no-result" id="no-result-guide">
                            <i class="bi bi-search" style="font-size:20px; display:block; margin-bottom:6px;"></i>
                            Menu tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-column">
                <div class="kasir-card p-4 summary-sticky">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="kasir-section-title">Ringkasan</h2>
                            <div class="kasir-section-subtitle">Total pesanan</div>
                        </div>
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(34,197,94,0.1);">
                            <i class="bi bi-basket" style="color:#16a34a;"></i>
                        </div>
                    </div>

                    <div id="order-empty" class="kasir-empty-state" style="padding:28px 12px;">
                        <i class="bi bi-cart"></i>
                        <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Belum ada menu</div>
                        <div class="text-xs">Pilih menu dari tab Customer atau Guide.</div>
                    </div>

                    <div class="summary-list-scroll">
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
                    </div>

                    <div id="grand-total-wrap" style="display:none;">
                        <div class="order-grand-total">
                            <span>Total</span>
                            <span id="grand-total">Rp0</span>
                        </div>
                    </div>

                    <button type="submit" class="kasir-btn kasir-btn-primary w-full mt-4">
                        <i class="bi bi-save"></i>
                        <span>Simpan & Cetak</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="hidden-inputs"></div>
</form>

<script>
    const pesananNormal = {};
    const pesananGuide  = {};
    const activeKategori = { normal: 'semua', guide: 'semua' };
    const activeSearch   = { normal: '', guide: '' };

    function switchMenuTab(grid) {
        document.querySelectorAll('.menu-tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.menuTab === grid);
        });

        document.querySelectorAll('.menu-panel').forEach(panel => {
            panel.classList.remove('active');
        });

        const target = document.getElementById(grid === 'normal' ? 'panel-normal' : 'panel-guide');
        if (target) {
            target.classList.add('active');
        }
    }

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
        let qty = parseInt(qtyEl.textContent) + delta;
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
        wrap.innerHTML = '';

        Object.entries(pesananNormal).forEach(([id, qty]) => {
            const card = document.querySelector(`#grid-normal .menu-card[data-id="${id}"]`);
            if (!card) return;
            wrap.innerHTML += `<input type="hidden" name="menu[]" value="${id}">`;
            wrap.innerHTML += `<input type="hidden" name="jumlah[]" value="${qty}">`;
            wrap.innerHTML += `<input type="hidden" name="harga_pakai[]" value="${card.dataset.harga}">`;
            wrap.innerHTML += `<input type="hidden" name="tipe_harga[]" value="normal">`;
        });

        Object.entries(pesananGuide).forEach(([id, qty]) => {
            const card = document.querySelector(`#grid-guide .menu-card-guide[data-id="${id}"]`);
            if (!card) return;
            wrap.innerHTML += `<input type="hidden" name="menu[]" value="${id}">`;
            wrap.innerHTML += `<input type="hidden" name="jumlah[]" value="${qty}">`;
            wrap.innerHTML += `<input type="hidden" name="harga_pakai[]" value="${card.dataset.harga}">`;
            wrap.innerHTML += `<input type="hidden" name="tipe_harga[]" value="guide">`;
        });
    }

    function updateOrderBar() {
        const hasNormal = Object.keys(pesananNormal).length > 0;
        const hasGuide  = Object.keys(pesananGuide).length > 0;

        document.getElementById('order-empty').style.display = (!hasNormal && !hasGuide) ? 'block' : 'none';
        document.getElementById('summary-normal').style.display = hasNormal ? 'block' : 'none';
        document.getElementById('summary-guide').style.display = hasGuide ? 'block' : 'none';
        document.getElementById('grand-total-wrap').style.display = (hasNormal || hasGuide) ? 'block' : 'none';

        let totalNormal = 0;
        let totalGuide  = 0;

        const listNormal = document.getElementById('list-normal');
        listNormal.innerHTML = Object.entries(pesananNormal).map(([id, qty]) => {
            const card  = document.querySelector(`#grid-normal .menu-card[data-id="${id}"]`);
            if (!card) return '';
            const harga = parseInt(card.dataset.harga);
            const sub   = harga * qty;
            totalNormal += sub;
            return `<div class="order-item">
                <span style="font-weight:700;">${card.dataset.nama} <strong>x${qty}</strong></span>
                <span style="font-weight:900; white-space:nowrap;">Rp${sub.toLocaleString('id-ID')}</span>
            </div>`;
        }).join('');
        document.getElementById('subtotal-normal').textContent = 'Rp' + totalNormal.toLocaleString('id-ID');

        const listGuide = document.getElementById('list-guide');
        listGuide.innerHTML = Object.entries(pesananGuide).map(([id, qty]) => {
            const card  = document.querySelector(`#grid-guide .menu-card-guide[data-id="${id}"]`);
            if (!card) return '';
            const harga = parseInt(card.dataset.harga);
            const sub   = harga * qty;
            totalGuide += sub;
            return `<div class="order-item">
                <span style="font-weight:700;">${card.dataset.nama} <strong>x${qty}</strong></span>
                <span style="font-weight:900; white-space:nowrap;">Rp${sub.toLocaleString('id-ID')}</span>
            </div>`;
        }).join('');
        document.getElementById('subtotal-guide').textContent = 'Rp' + totalGuide.toLocaleString('id-ID');

        document.getElementById('grand-total').textContent = 'Rp' + (totalNormal + totalGuide).toLocaleString('id-ID');
    }

    function filterKategori(grid, kategori, btn) {
        activeKategori[grid] = kategori;

        const panel = btn.closest('.menu-panel');
        if (panel) {
            panel.querySelectorAll('.kategori-chip').forEach(b => {
                b.classList.remove('active', 'guide-active');
            });
        }

        btn.classList.add(grid === 'guide' ? 'guide-active' : 'active');
        applyFilter(grid);
    }

    function filterGrid(grid, value) {
        activeSearch[grid] = value.toLowerCase().trim();
        applyFilter(grid);
    }

    function applyFilter(grid) {
        const selector  = grid === 'normal' ? '#grid-normal .menu-card' : '#grid-guide .menu-card-guide';
        const noResult  = document.getElementById(`no-result-${grid}`);
        const kat       = activeKategori[grid];
        const keyword   = activeSearch[grid];
        let visible = 0;

        document.querySelectorAll(selector).forEach(card => {
            const matchKat    = kat === 'semua' || card.dataset.kategori == kat;
            const matchSearch = card.dataset.nama.toLowerCase().includes(keyword);
            const show        = matchKat && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        noResult.style.display = visible === 0 ? 'block' : 'none';
    }
</script>

@endsection
