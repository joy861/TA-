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
        height: 36px;
        padding: 0 16px;
        border-radius: 999px;
        border: 1.5px solid rgba(30,58,95,0.12);
        background: #fff;
        color: rgba(30,58,95,0.6);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
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

    .menu-card {
        background: #fff;
        border: 1.5px solid rgba(30,58,95,0.1);
        border-radius: 16px;
        padding: 14px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        min-height: 130px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .menu-card:hover {
        border-color: #60a5fa;
        transform: translateY(-1px);
    }

    .menu-card.selected {
        border-color: #1e3a5f;
        background: #eef2ff;
    }

    .check-badge {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
        width: 22px;
        height: 22px;
        border-radius: 999px;
        background: #1e3a5f;
        color: #60a5fa;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 900;
    }

    .menu-card.selected .check-badge { display: flex; }

    .menu-nama {
        font-size: 14px;
        font-weight: 800;
        color: #1e3a5f;
        padding-right: 28px;
        line-height: 1.3;
    }

    .menu-harga {
        font-size: 12px;
        color: rgba(30,58,95,0.55);
        font-weight: 700;
        margin-top: 4px;
    }

    .qty-control {
        display: none;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
    }

    .menu-card.selected .qty-control { display: flex; }

    .qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: none;
        background: #1e3a5f;
        color: #60a5fa;
        font-size: 14px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-btn:hover { background: #60a5fa; color: #1e3a5f; }

    .qty-num {
        min-width: 22px;
        text-align: center;
        font-size: 14px;
        font-weight: 900;
        color: #1e3a5f;
    }

    .order-bar {
        background: #eef2ff;
        border-radius: 14px;
        padding: 14px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 6px 0;
        font-size: 13px;
        color: #1e3a5f;
    }

    .order-total {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        border-top: 1.5px solid rgba(30,58,95,0.1);
        margin-top: 8px;
        padding-top: 12px;
        font-size: 16px;
        font-weight: 900;
        color: #1e3a5f;
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
        <div class="kasir-page-subtitle">Pilih meja dan menu yang dipesan pelanggan</div>
    </div>
    <a href="{{ $backUrl }}" class="kasir-btn kasir-btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>
</div>

<form action="{{ route('pesanan.store') }}" method="POST" id="form-pesanan">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        <div class="xl:col-span-2 space-y-5">

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
                <label class="kasir-form-label">Nomor Meja</label>
                <select name="id_meja" class="kasir-select" required>
                    <option value="">-- Pilih Meja --</option>
                    @foreach($meja as $m)
                        <option value="{{ $m->id_meja }}">
                            Meja {{ $m->nomor_meja }} — {{ $m->kapasitas }} orang
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PILIH MENU --}}
            <div class="kasir-card p-5">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="kasir-section-title">Pilih Menu</h2>
                        <div class="kasir-section-subtitle">Klik menu untuk memilih, lalu atur jumlahnya</div>
                    </div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#60a5fa;">
                        <i class="bi bi-journal-text" style="color:#1e3a5f;"></i>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-4">
                    <button type="button" class="kategori-chip active" onclick="filterKategori('semua', this)">
                        Semua
                    </button>
                    @foreach($kategori as $kat)
                        <button type="button" class="kategori-chip"
                                onclick="filterKategori('{{ (int) $kat->id_kategori }}', this)">
                            {{ $kat->nama_kategori }}
                        </button>
                    @endforeach
                </div>

                <div class="pt-4" style="border-top:1px solid rgba(30,58,95,0.06);">
                    <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($menu as $mn)
                            <div class="menu-card"
                                 data-id="{{ $mn->id_menu }}"
                                 data-kategori="{{ $mn->id_kategori }}"
                                 data-harga="{{ $mn->harga }}"
                                 data-nama="{{ $mn->nama_menu }}"
                                 onclick="toggleMenu(this)">
                                <div class="check-badge">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <div>
                                    <div class="menu-nama">{{ $mn->nama_menu }}</div>
                                    <div class="menu-harga">Rp{{ number_format($mn->harga, 0, ',', '.') }}</div>
                                </div>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" onclick="ubahQty(event, this, -1)">−</button>
                                    <span class="qty-num">1</span>
                                    <button type="button" class="qty-btn" onclick="ubahQty(event, this, 1)">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="hidden-inputs"></div>
            </div>
        </div>

        {{-- RINGKASAN --}}
        <div class="xl:col-span-1">
            <div class="kasir-card p-5 sticky top-[88px]">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="kasir-section-title">Ringkasan</h2>
                        <div class="kasir-section-subtitle">Pesanan yang dipilih</div>
                    </div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(34,197,94,0.1);">
                        <i class="bi bi-basket" style="color:#16a34a;"></i>
                    </div>
                </div>

                <div id="order-empty" class="kasir-empty-state" style="padding:24px 16px;">
                    <i class="bi bi-cart"></i>
                    <div class="text-sm font-bold mb-0.5" style="color:#1e3a5f;">Belum ada menu</div>
                    <div class="text-xs">Pilih menu terlebih dahulu.</div>
                </div>

                <div id="order-bar" class="order-bar" style="display:none;">
                    <div class="kasir-form-label">Pesanan Dipilih</div>
                    <div id="order-list"></div>
                    <div class="order-total">
                        <span>Total</span>
                        <span id="total-harga">Rp0</span>
                    </div>
                </div>

                <button type="submit" class="kasir-btn kasir-btn-primary w-full mt-4">
                    <i class="bi bi-save"></i>
                    <span>Simpan Pesanan</span>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    const pesanan = {};

    function toggleMenu(card) {
        const id = card.dataset.id;
        if (pesanan[id]) {
            delete pesanan[id];
            card.classList.remove('selected');
            card.querySelector('.qty-num').textContent = 1;
        } else {
            pesanan[id] = 1;
            card.classList.add('selected');
        }
        updateHidden(); updateOrderBar();
    }

    function ubahQty(event, btn, delta) {
        event.stopPropagation();
        const card = btn.closest('.menu-card');
        const id = card.dataset.id;
        const qtyEl = card.querySelector('.qty-num');
        let qty = parseInt(qtyEl.textContent) + delta;
        if (qty < 1) {
            delete pesanan[id];
            card.classList.remove('selected');
            qtyEl.textContent = 1;
        } else {
            pesanan[id] = qty;
            qtyEl.textContent = qty;
        }
        updateHidden(); updateOrderBar();
    }

    function updateHidden() {
        const wrap = document.getElementById('hidden-inputs');
        wrap.innerHTML = '';
        Object.entries(pesanan).forEach(([id, qty]) => {
            wrap.innerHTML += `<input type="hidden" name="menu[]" value="${id}">`;
            wrap.innerHTML += `<input type="hidden" name="jumlah[]" value="${qty}">`;
        });
    }

    function updateOrderBar() {
        const bar = document.getElementById('order-bar');
        const empty = document.getElementById('order-empty');
        const list = document.getElementById('order-list');
        const ids = Object.keys(pesanan);
        if (!ids.length) { bar.style.display = 'none'; empty.style.display = 'block'; return; }
        bar.style.display = 'block'; empty.style.display = 'none';
        let total = 0;
        list.innerHTML = ids.map(id => {
            const card = document.querySelector(`.menu-card[data-id="${id}"]`);
            const harga = parseInt(card.dataset.harga);
            const qty = pesanan[id];
            const sub = harga * qty;
            total += sub;
            return `<div class="order-item"><span>${card.dataset.nama} <strong>x${qty}</strong></span><span><strong>Rp${sub.toLocaleString('id-ID')}</strong></span></div>`;
        }).join('');
        document.getElementById('total-harga').textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    function filterKategori(kategori, btn) {
        document.querySelectorAll('.kategori-chip').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.menu-card').forEach(card => {
            const tampil = kategori === 'semua' || card.dataset.kategori == kategori;
            card.style.display = tampil ? '' : 'none';
        });
    }
</script>

@endsection