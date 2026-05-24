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
        min-height: 145px;
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
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 900;
        background: #1e3a5f;
        color: #60a5fa;
    }

    .menu-card.selected .check-badge {
        display: flex;
    }

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

    .menu-card.selected .qty-control {
        display: flex;
    }

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

    .qty-btn:hover:not(:disabled) {
        background: #60a5fa;
        color: #1e3a5f;
    }

    .qty-btn:disabled {
        background: rgba(30,58,95,0.1);
        color: rgba(30,58,95,0.3);
        cursor: not-allowed;
    }

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

    /* =========================
       MODAL KONFIRMASI
    ========================= */
    .confirm-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(8, 20, 43, 0.62);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease;
    }

    .confirm-modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .confirm-modal-box {
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        border-radius: 28px;
        padding: 34px 28px 26px;
        text-align: center;
        box-shadow: 0 30px 80px rgba(8, 20, 43, 0.28);
        border: 1px solid rgba(226, 232, 240, 0.9);
        transform: translateY(20px) scale(0.96);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .confirm-modal-overlay.show .confirm-modal-box {
        transform: translateY(0) scale(1);
    }

    .confirm-modal-box::before {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: rgba(96, 165, 250, 0.10);
        top: -90px;
        right: -90px;
    }

    .confirm-modal-icon {
        width: 84px;
        height: 84px;
        margin: 0 auto 20px;
        border-radius: 999px;
        background: #fff7ed;
        border: 4px solid #fed7aa;
        color: #f59e0b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        font-weight: 900;
        position: relative;
        z-index: 2;
    }

    .confirm-modal-title {
        font-size: 28px;
        font-weight: 900;
        color: #1e3a5f;
        margin-bottom: 10px;
        letter-spacing: -0.04em;
        position: relative;
        z-index: 2;
    }

    .confirm-modal-text {
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .confirm-modal-subtext {
        font-size: 14px;
        color: #7188a7;
        line-height: 1.6;
        margin-bottom: 26px;
        position: relative;
        z-index: 2;
    }

    .confirm-modal-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        position: relative;
        z-index: 2;
    }

    .confirm-btn {
        border: none;
        border-radius: 16px;
        padding: 13px 20px;
        min-width: 125px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .confirm-btn-cancel {
        background: #eef2f7;
        color: #475569;
    }

    .confirm-btn-cancel:hover {
        background: #e2e8f0;
    }

    .confirm-btn-submit {
        background: linear-gradient(135deg, #1e3a5f, #2563eb);
        color: #ffffff;
        box-shadow: 0 14px 30px rgba(37, 99, 235, 0.24);
    }

    .confirm-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 35px rgba(37, 99, 235, 0.30);
    }

    .confirm-success-state {
        display: none;
        position: relative;
        z-index: 2;
    }

    .confirm-modal-box.success-mode .confirm-normal-state {
        display: none;
    }

    .confirm-modal-box.success-mode .confirm-success-state {
        display: block;
    }

    .success-check-wrap {
        width: 92px;
        height: 92px;
        margin: 0 auto 20px;
        border-radius: 999px;
        background: #ecfdf5;
        border: 4px solid #bbf7d0;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: popSuccess 0.35s ease forwards;
    }

    .success-check {
        width: 42px;
        height: 22px;
        border-left: 6px solid #16a34a;
        border-bottom: 6px solid #16a34a;
        transform: rotate(-45deg) scale(0);
        animation: drawCheck 0.35s ease 0.18s forwards;
    }

    .success-title {
        font-size: 28px;
        font-weight: 900;
        color: #1e3a5f;
        margin-bottom: 8px;
        letter-spacing: -0.04em;
    }

    .success-text {
        font-size: 14px;
        color: #7188a7;
        font-weight: 600;
    }

    @keyframes popSuccess {
        0% { transform: scale(0.75); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes drawCheck {
        0% { transform: rotate(-45deg) scale(0); opacity: 0; }
        100% { transform: rotate(-45deg) scale(1); opacity: 1; }
    }

    @media (max-width: 576px) {
        .confirm-modal-box {
            padding: 28px 20px 22px;
            border-radius: 22px;
        }

        .confirm-modal-title,
        .success-title {
            font-size: 24px;
        }

        .confirm-modal-actions {
            flex-direction: column-reverse;
        }

        .confirm-btn {
            width: 100%;
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        <div class="xl:col-span-2 space-y-5">

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

            <div class="kasir-card p-5">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="kasir-section-title">Pilih Menu</h2>
                        <div class="kasir-section-subtitle">Pilih dan atur jumlah menu pesanan</div>
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
                        <button type="button"
                                class="kategori-chip"
                                onclick="filterKategori('{{ (int) $kat->id_kategori }}', this)">
                            {{ $kat->nama_kategori }}
                        </button>
                    @endforeach
                </div>

                <div class="pt-4" style="border-top:1px solid rgba(30,58,95,0.06);">
                    <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($menu as $mn)
                            @php
                                $detail = $pesanan->detailPesanan->firstWhere('id_menu', $mn->id_menu);
                                $isSelected = $detail !== null;
                                $qty = $detail ? $detail->jumlah : 1;
                            @endphp

                            <div class="menu-card {{ $isSelected ? 'selected' : '' }}"
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

                                <div>
                                    <div class="qty-control">
                                        <button type="button"
                                                class="qty-btn btn-kurang"
                                                onclick="ubahQty(event, this, -1)">
                                            −
                                        </button>

                                        <span class="qty-num">{{ $qty }}</span>

                                        <button type="button"
                                                class="qty-btn btn-tambah"
                                                onclick="ubahQty(event, this, 1)">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="hidden-inputs"></div>
            </div>
        </div>

        <div class="xl:col-span-1">
            <div class="kasir-card p-5 sticky top-[88px]">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="kasir-section-title">Ringkasan</h2>
                        <div class="kasir-section-subtitle">Total pesanan saat ini</div>
                    </div>

                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(34,197,94,0.1);">
                        <i class="bi bi-basket" style="color:#16a34a;"></i>
                    </div>
                </div>

                <div id="order-empty" class="kasir-empty-state" style="display:none; padding:24px 16px;">
                    <i class="bi bi-cart"></i>
                    <div class="text-sm font-bold" style="color:#1e3a5f;">Belum ada menu</div>
                </div>

                <div id="order-bar" class="order-bar" style="display:none;">
                    <div class="kasir-form-label">Ringkasan Pesanan</div>
                    <div id="order-list"></div>

                    <div class="order-total">
                        <span>Total</span>
                        <span id="total-harga">Rp0</span>
                    </div>
                </div>

                <button type="button" class="kasir-btn kasir-btn-primary w-full mt-4" onclick="openConfirmUpdateModal()">
                    <i class="bi bi-save"></i>
                    <span>Update Pesanan</span>
                </button>
            </div>
        </div>
    </div>
</form>

{{-- MODAL KONFIRMASI UPDATE --}}
<div id="confirmUpdateModal" class="confirm-modal-overlay">
    <div id="confirmUpdateBox" class="confirm-modal-box">

        <div class="confirm-normal-state">
            <div class="confirm-modal-icon">
                !
            </div>

            <h3 class="confirm-modal-title">Konfirmasi Pesanan</h3>

            <p class="confirm-modal-text">
                Yakin ingin menyimpan perubahan?
            </p>

            <p class="confirm-modal-subtext">
                Data pesanan akan diperbarui sesuai menu yang dipilih saat ini.
            </p>

            <div class="confirm-modal-actions">
                <button type="button" class="confirm-btn confirm-btn-cancel" onclick="closeConfirmUpdateModal()">
                    Batal
                </button>

                <button type="button" class="confirm-btn confirm-btn-submit" onclick="confirmSubmitUpdatePesanan()">
                    Ya, Update
                </button>
            </div>
        </div>

        <div class="confirm-success-state">
            <div class="success-check-wrap">
                <div class="success-check"></div>
            </div>

            <h3 class="success-title">Berhasil</h3>

            <p class="success-text">
                Pesanan sedang diperbarui...
            </p>
        </div>

    </div>
</div>

<script>
    const pesanan = {};

    // Inisialisasi dari menu yang sudah dipilih
    document.querySelectorAll('.menu-card.selected').forEach(card => {
        const id = card.dataset.id;
        const qty = parseInt(card.querySelector('.qty-num').textContent);
        pesanan[id] = {
            qty,
            nama: card.dataset.nama,
            harga: parseInt(card.dataset.harga)
        };
    });

    // Inject hidden input id_detail[] untuk detail pesanan yang sudah ada
    @foreach($pesanan->detailPesanan as $d)
        (function() {
            const wrap = document.getElementById('hidden-inputs');
            const hid = document.createElement('input');
            hid.type = 'hidden';
            hid.name = 'id_detail[]';
            hid.value = '{{ $d->id_detail }}';
            hid.dataset.forMenu = '{{ $d->id_menu }}';
            wrap.appendChild(hid);
        })();
    @endforeach

    updateHidden();
    updateOrderBar();

    function toggleMenu(card) {
        const id = card.dataset.id;

        if (pesanan[id]) {
            delete pesanan[id];
            card.classList.remove('selected');
            card.querySelector('.qty-num').textContent = 1;
        } else {
            pesanan[id] = {
                qty: 1,
                nama: card.dataset.nama,
                harga: parseInt(card.dataset.harga)
            };
            card.classList.add('selected');
        }

        updateHidden();
        updateOrderBar();
    }

    function ubahQty(event, btn, delta) {
        event.stopPropagation();

        const card = btn.closest('.menu-card');
        const id = card.dataset.id;
        const qtyEl = card.querySelector('.qty-num');

        if (!pesanan[id]) return;

        let qty = pesanan[id].qty + delta;

        if (qty < 1) {
            // Kurang dari 1 = hapus dari pesanan
            delete pesanan[id];
            card.classList.remove('selected');
            qtyEl.textContent = 1;
            updateHidden();
            updateOrderBar();
            return;
        }

        pesanan[id].qty = qty;
        qtyEl.textContent = qty;

        updateHidden();
        updateOrderBar();
    }

    function updateHidden() {
        const wrap = document.getElementById('hidden-inputs');

        // Hapus input menu[] dan jumlah[] lama
        wrap.querySelectorAll('input[name="menu[]"], input[name="jumlah[]"]').forEach(el => el.remove());

        Object.entries(pesanan).forEach(([id, data]) => {
            const m = document.createElement('input');
            m.type = 'hidden';
            m.name = 'menu[]';
            m.value = id;
            wrap.appendChild(m);

            const j = document.createElement('input');
            j.type = 'hidden';
            j.name = 'jumlah[]';
            j.value = data.qty;
            wrap.appendChild(j);
        });
    }

    function updateOrderBar() {
        const bar = document.getElementById('order-bar');
        const empty = document.getElementById('order-empty');
        const list = document.getElementById('order-list');
        const ids = Object.keys(pesanan);

        if (!ids.length) {
            bar.style.display = 'none';
            empty.style.display = 'block';
            return;
        }

        bar.style.display = 'block';
        empty.style.display = 'none';

        let total = 0;

        list.innerHTML = ids.map(id => {
            const d = pesanan[id];
            const sub = d.harga * d.qty;
            total += sub;

            return `
                <div class="order-item">
                    <span>${d.nama} <strong>x${d.qty}</strong></span>
                    <span><strong>Rp${sub.toLocaleString('id-ID')}</strong></span>
                </div>
            `;
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

    function openConfirmUpdateModal() {
        const form = document.getElementById('formUpdatePesanan');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const modal = document.getElementById('confirmUpdateModal');
        const box = document.getElementById('confirmUpdateBox');

        box.classList.remove('success-mode');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmUpdateModal() {
        const modal = document.getElementById('confirmUpdateModal');
        const box = document.getElementById('confirmUpdateBox');

        modal.classList.remove('show');
        box.classList.remove('success-mode');
        document.body.style.overflow = '';
    }

    function confirmSubmitUpdatePesanan() {
        const box = document.getElementById('confirmUpdateBox');
        const form = document.getElementById('formUpdatePesanan');

        box.classList.add('success-mode');

        setTimeout(function () {
            form.submit();
        }, 900);
    }

    document.getElementById('confirmUpdateModal').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmUpdateModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeConfirmUpdateModal();
    });
</script>

@endsection