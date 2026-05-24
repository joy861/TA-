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

    .search-input.guide-search:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
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

    /* Menu card normal */
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
    .menu-card.habis { opacity: 0.4; pointer-events: none; }

    /* Menu card guide */
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
    .menu-card-guide.habis { opacity: 0.4; pointer-events: none; }

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

    .order-bar {
        border-radius: 12px;
        padding: 12px;
    }

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

    .panel-header-normal {
        background: #1e3a5f;
        color: #fff;
    }

    .panel-header-guide {
        background: #60a5fa;
        color: #1e3a5f;
    }

    .no-result {
        display: none;
        text-align: center;
        padding: 24px 12px;
        color: rgba(30,58,95,0.3);
        font-size: 12px;
    }

    .menu-scroll {
        max-height: 480px;
        overflow-y: auto;
        padding-right: 2px;
    }

    .menu-scroll::-webkit-scrollbar { width: 4px; }
    .menu-scroll::-webkit-scrollbar-track { background: transparent; }
    .menu-scroll::-webkit-scrollbar-thumb { background: rgba(30,58,95,0.15); border-radius: 999px; }
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
        <div class="kasir-page-subtitle">Pilih meja, lalu tambahkan menu dari kolom Customer atau Guide</div>
    </div>
    <a href="{{ $backUrl }}" class="kasir-btn kasir-btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>
</div>

<form action="{{ route('pesanan.store') }}" method="POST" id="form-pesanan">
    @csrf

    <div class="space-y-5">

        {{-- PILIH MEJA --}}
        <div class="kasir-card p-5">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="kasir-section-title">Pilih Meja</h2>
                    <div class="kasir-section-subtitle">Tentukan meja pelanggan yang sedang memesan</div>
                </div>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#eef2ff;">
                    <i class="bi bi-grid-3x3-gap" style="color:#1e3a5f;"></i>
                </div>
            </div>
            <select name="id_meja" class="kasir-select" required>
                <option value="">-- Pilih Meja --</option>
                @foreach($meja as $m)
                    <option value="{{ $m->id_meja }}">
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

                {{-- Search Normal --}}
                <div class="search-wrapper mb-3">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Cari menu..."
                           oninput="filterGrid('normal', this.value)">
                </div>

                {{-- Filter Kategori Normal --}}
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

            {{-- KANAN: HARGA GUIDE --}}
            <div class="xl:col-span-2 kasir-card p-4">
                <div class="panel-header panel-header-guide">
                    <i class="bi bi-star-fill"></i>
                    <span>Menu Guide (Harga Guide)</span>
                </div>

                {{-- Search Guide --}}
                <div class="search-wrapper mb-3">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input guide-search" placeholder="Cari menu..."
                           oninput="filterGrid('guide', this.value)">
                </div>

                {{-- Filter Kategori Guide --}}
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

            {{-- RINGKASAN --}}
            <div class="xl:col-span-1">
                <div class="kasir-card p-4 sticky top-[88px]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="kasir-section-title">Ringkasan</h2>
                            <div class="kasir-section-subtitle">Total pesanan</div>
                        </div>
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(34,197,94,0.1);">
                            <i class="bi bi-basket" style="color:#16a34a;"></i>
                        </div>
                    </div>

                    <div id="order-empty" class="kasir-empty-state" style="padding:20px 12px;">
                        <i class="bi bi-cart"></i>
                        <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Belum ada menu</div>
                        <div class="text-xs">Pilih dari kolom kiri atau kanan.</div>
                    </div>

                    {{-- Ringkasan Normal --}}
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

                    {{-- Ringkasan Guide --}}
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

                    {{-- Grand Total --}}
                    <div id="grand-total-wrap" style="display:none;">
                        <div class="order-grand-total">
                            <span>Total</span>
                            <span id="grand-total">Rp0</span>
                        </div>
                    </div>

                    <button type="submit" class="kasir-btn kasir-btn-primary w-full mt-4">
                        <i class="bi bi-save"></i>
                        <span>Simpan & Cetak Pesanan</span>
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
            wrap.innerHTML += `<input type="hidden" name="menu[]" value="${id}">`;
            wrap.innerHTML += `<input type="hidden" name="jumlah[]" value="${qty}">`;
            wrap.innerHTML += `<input type="hidden" name="harga_pakai[]" value="${card.dataset.harga}">`;
            wrap.innerHTML += `<input type="hidden" name="tipe_harga[]" value="normal">`;
        });

        Object.entries(pesananGuide).forEach(([id, qty]) => {
            const card = document.querySelector(`#grid-guide .menu-card-guide[data-id="${id}"]`);
            wrap.innerHTML += `<input type="hidden" name="menu[]" value="${id}">`;
            wrap.innerHTML += `<input type="hidden" name="jumlah[]" value="${qty}">`;
            wrap.innerHTML += `<input type="hidden" name="harga_pakai[]" value="${card.dataset.harga}">`;
            wrap.innerHTML += `<input type="hidden" name="tipe_harga[]" value="guide">`;
        });
    }

    function updateOrderBar() {
        const hasNormal = Object.keys(pesananNormal).length > 0;
        const hasGuide  = Object.keys(pesananGuide).length > 0;

        document.getElementById('order-empty').style.display   = (!hasNormal && !hasGuide) ? 'block' : 'none';
        document.getElementById('summary-normal').style.display = hasNormal ? 'block' : 'none';
        document.getElementById('summary-guide').style.display  = hasGuide  ? 'block' : 'none';
        document.getElementById('grand-total-wrap').style.display = (hasNormal || hasGuide) ? 'block' : 'none';

        let totalNormal = 0;
        let totalGuide  = 0;

        // Render normal
        const listNormal = document.getElementById('list-normal');
        listNormal.innerHTML = Object.entries(pesananNormal).map(([id, qty]) => {
            const card  = document.querySelector(`#grid-normal .menu-card[data-id="${id}"]`);
            const harga = parseInt(card.dataset.harga);
            const sub   = harga * qty;
            totalNormal += sub;
            return `<div class="order-item">
                <span style="font-weight:600;">${card.dataset.nama} <strong>x${qty}</strong></span>
                <span style="font-weight:800; white-space:nowrap;">Rp${sub.toLocaleString('id-ID')}</span>
            </div>`;
        }).join('');
        document.getElementById('subtotal-normal').textContent = 'Rp' + totalNormal.toLocaleString('id-ID');

        // Render guide
        const listGuide = document.getElementById('list-guide');
        listGuide.innerHTML = Object.entries(pesananGuide).map(([id, qty]) => {
            const card  = document.querySelector(`#grid-guide .menu-card-guide[data-id="${id}"]`);
            const harga = parseInt(card.dataset.harga);
            const sub   = harga * qty;
            totalGuide += sub;
            return `<div class="order-item">
                <span style="font-weight:600;">${card.dataset.nama} <strong>x${qty}</strong></span>
                <span style="font-weight:800; white-space:nowrap;">Rp${sub.toLocaleString('id-ID')}</span>
            </div>`;
        }).join('');
        document.getElementById('subtotal-guide').textContent = 'Rp' + totalGuide.toLocaleString('id-ID');

        // Grand total
        document.getElementById('grand-total').textContent = 'Rp' + (totalNormal + totalGuide).toLocaleString('id-ID');
    }

    function filterKategori(grid, kategori, btn) {
        activeKategori[grid] = kategori;
        const scope = grid === 'normal' ? '#grid-normal' : '#grid-guide';

        // Reset chip style
        document.querySelectorAll(`#${grid === 'normal' ? 'grid-normal' : 'grid-guide'}`).forEach(() => {});
        btn.closest('.kasir-card, .xl\\:col-span-2').querySelectorAll('.kategori-chip').forEach(b => {
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