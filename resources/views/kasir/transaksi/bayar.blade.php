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

    .kasir-input,
    .kasir-select {
        width: 100%;
        height: 46px;
        border-radius: 12px;
        border: 1.5px solid rgba(30,58,95,0.12);
        background: #fff;
        color: #1e3a5f;
        padding: 0 14px;
        font-size: 14px;
        font-weight: 700;
        outline: none;
        transition: all 0.2s ease;
    }

    .kasir-input:focus,
    .kasir-select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.12);
    }

    .kasir-input[readonly] {
        background: #eef2ff;
        color: rgba(30,58,95,0.6);
        cursor: not-allowed;
    }

    .payment-method-card {
        border: 1.5px solid rgba(30,58,95,0.1);
        border-radius: 14px;
        padding: 14px;
        background: #fff;
        transition: all 0.2s ease;
    }

    .payment-method-card.active-cash {
        border-color: #16a34a;
        background: rgba(34,197,94,0.04);
    }

    .payment-method-card.active-qris {
        border-color: #60a5fa;
        background: #eef2ff;
    }

    .payment-total-box {
        background: #1e3a5f;
        border-radius: 18px;
        padding: 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .payment-total-box::after {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        border-radius: 999px;
        background: rgba(96,165,250,0.15);
        right: -40px;
        top: -50px;
    }

    .payment-total-label {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .payment-total-value {
        font-size: 32px;
        line-height: 1;
        font-weight: 900;
        letter-spacing: -1px;
        position: relative;
        z-index: 2;
    }

    .payment-total-value .rp {
        font-size: 14px;
        font-weight: 700;
        color: rgba(255,255,255,0.5);
        margin-right: 4px;
    }

    .payment-info-card {
        background: #eef2ff;
        border-radius: 12px;
        padding: 12px 14px;
    }

    .payment-modal-overlay {
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

    .payment-modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .payment-modal-box {
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

    .payment-modal-overlay.show .payment-modal-box {
        transform: translateY(0) scale(1);
    }

    .payment-modal-box::before {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: rgba(34, 197, 94, 0.10);
        top: -90px;
        right: -90px;
    }

    .payment-modal-icon {
        width: 84px;
        height: 84px;
        margin: 0 auto 20px;
        border-radius: 999px;
        background: #ecfdf5;
        border: 4px solid #bbf7d0;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        position: relative;
        z-index: 2;
    }

    .payment-modal-title {
        font-size: 28px;
        font-weight: 900;
        color: #1e3a5f;
        margin-bottom: 10px;
        letter-spacing: -0.04em;
        position: relative;
        z-index: 2;
    }

    .payment-modal-text {
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .payment-modal-subtext {
        font-size: 14px;
        color: #7188a7;
        line-height: 1.6;
        margin-bottom: 26px;
        position: relative;
        z-index: 2;
    }

    .payment-modal-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        position: relative;
        z-index: 2;
    }

    .payment-btn {
        border: none;
        border-radius: 16px;
        padding: 13px 20px;
        min-width: 125px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .payment-btn-cancel {
        background: #eef2f7;
        color: #475569;
    }

    .payment-btn-submit {
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #ffffff;
        box-shadow: 0 14px 30px rgba(22, 163, 74, 0.24);
    }

    .payment-success-state {
        display: none;
        position: relative;
        z-index: 2;
    }

    .payment-modal-box.success-mode .payment-normal-state {
        display: none;
    }

    .payment-modal-box.success-mode .payment-success-state {
        display: block;
    }

    .payment-success-check-wrap {
        width: 92px;
        height: 92px;
        margin: 0 auto 20px;
        border-radius: 999px;
        background: #ecfdf5;
        border: 4px solid #bbf7d0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .payment-success-check {
        width: 42px;
        height: 22px;
        border-left: 6px solid #16a34a;
        border-bottom: 6px solid #16a34a;
        transform: rotate(-45deg);
    }

    .payment-success-title {
        font-size: 28px;
        font-weight: 900;
        color: #1e3a5f;
        margin-bottom: 8px;
    }

    .payment-success-text {
        font-size: 14px;
        color: #7188a7;
        font-weight: 600;
    }

    @media (max-width: 576px) {
        .payment-modal-box {
            padding: 28px 20px 22px;
            border-radius: 22px;
        }

        .payment-modal-actions {
            flex-direction: column-reverse;
        }

        .payment-btn {
            width: 100%;
        }
    }
</style>

@php
    $backUrl = \Illuminate\Support\Facades\Route::has('pesanan.index')
        ? route('pesanan.index')
        : url('pesanan');

    $total = $pesanan->total_harga ?? 0;
@endphp

<div class="kasir-page-header">
    <div>
        <p class="kasir-page-eyebrow">PEMBAYARAN</p>
        <h1 class="kasir-page-title">Pembayaran</h1>
        <div class="kasir-page-subtitle">Proses pembayaran pesanan dengan cash atau QRIS</div>
    </div>

    <a href="{{ $backUrl }}" class="kasir-btn kasir-btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    <div class="xl:col-span-2 space-y-5">
        <div class="kasir-card p-5">
            <div class="flex items-start justify-between gap-4 mb-5">
                <div>
                    <h2 class="kasir-section-title">Detail Pesanan</h2>
                    <div class="kasir-section-subtitle">Informasi meja, tanggal, dan menu yang dipesan</div>
                </div>

                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#eef2ff;">
                    <i class="bi bi-receipt-cutoff" style="color:#1e3a5f;"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-5">
                <div class="payment-info-card">
                    <div class="kasir-form-label" style="margin-bottom:4px;">Meja</div>
                    <div class="text-base font-black" style="color:#1e3a5f;">
                        Meja {{ $pesanan->meja->nomor_meja ?? '-' }}
                    </div>
                </div>

                <div class="payment-info-card">
                    <div class="kasir-form-label" style="margin-bottom:4px;">Tanggal</div>
                    <div class="text-base font-black" style="color:#1e3a5f;">
                        {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <div class="rounded-xl overflow-hidden" style="border:1px solid rgba(30,58,95,0.06);">
                <div class="kasir-table-wrap">
                    <table class="kasir-table" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($pesanan->detailPesanan as $d)
                                @php
                                    $harga = $d->menu->harga ?? 0;
                                    $jumlah = $d->jumlah ?? 0;
                                    $subtotal = $d->subtotal ?? ($harga * $jumlah);
                                @endphp

                                <tr>
                                    <td class="font-bold">{{ $d->menu->nama_menu ?? '-' }}</td>
                                    <td>Rp{{ number_format($harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="kasir-badge kasir-badge-info">{{ $jumlah }}x</span>
                                    </td>
                                    <td class="font-black">
                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-1">
        <div class="kasir-card p-5 sticky top-[88px]">

            <div class="payment-total-box mb-5">
                <div class="payment-total-label">Total Pembayaran</div>
                <div class="payment-total-value">
                    <span class="rp">Rp</span>{{ number_format($total, 0, ',', '.') }}
                </div>
            </div>

            <form action="{{ route('transaksi.proses', $pesanan->id_pesanan) }}" method="POST" id="formPembayaran">
                @csrf

                <input type="hidden" id="total" value="{{ $total }}">

                <div class="mb-4">
                    <label class="kasir-form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="kasir-select" required onchange="ubahMetodePembayaran()">
                        <option value="cash">💵 Cash / Tunai</option>
                        <option value="qris">📱 QRIS</option>
                    </select>
                </div>

                <div id="paymentMethodInfo" class="payment-method-card active-cash mb-4">
                    <div class="flex items-center gap-3">
                        <div id="paymentMethodIconWrap"
                             class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:rgba(34,197,94,0.15);">
                            <i id="paymentMethodIcon" class="bi bi-cash-coin" style="color:#16a34a;"></i>
                        </div>

                        <div class="min-w-0">
                            <div id="paymentMethodTitle" class="text-sm font-black" style="color:#1e3a5f;">
                                Pembayaran Cash
                            </div>
                            <div id="paymentMethodDesc" class="text-xs" style="color:rgba(30,58,95,0.5);">
                                Jumlah bayar bisa diubah jika uang lebih.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="kasir-form-label">Jumlah Bayar</label>
                    <input type="number"
                           name="bayar"
                           id="bayar"
                           class="kasir-input"
                           required
                           min="{{ $total }}"
                           value="{{ $total }}"
                           onkeyup="hitungKembalian()"
                           onchange="hitungKembalian()">
                </div>

                <div class="mb-5">
                    <label class="kasir-form-label">Kembalian</label>
                    <input type="text"
                           id="kembalian"
                           class="kasir-input"
                           value="Rp0"
                           readonly
                           style="font-size:16px; font-weight:900;">
                </div>

                <button type="button"
                        class="kasir-btn kasir-btn-success w-full"
                        style="height:48px; font-size:14px;"
                        onclick="openPaymentConfirmModal()">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Proses Pembayaran</span>
                </button>
            </form>
        </div>
    </div>
</div>

<div id="paymentConfirmModal" class="payment-modal-overlay">
    <div id="paymentConfirmBox" class="payment-modal-box">

        <div class="payment-normal-state">
            <div class="payment-modal-icon">
                <i class="bi bi-cash-coin"></i>
            </div>

            <h3 class="payment-modal-title">Konfirmasi Pembayaran</h3>

            <p class="payment-modal-text">
                Yakin ingin memproses pembayaran ini?
            </p>

            <p class="payment-modal-subtext">
                Setelah diproses, status pesanan akan berubah menjadi sudah bayar dan meja akan dikosongkan.
            </p>

            <div class="payment-modal-actions">
                <button type="button" class="payment-btn payment-btn-cancel" onclick="closePaymentConfirmModal()">
                    Batal
                </button>

                <button type="button" class="payment-btn payment-btn-submit" id="btnSubmitPembayaran" onclick="submitPembayaran()">
                    Ya, Proses
                </button>
            </div>
        </div>

        <div class="payment-success-state">
            <div class="payment-success-check-wrap">
                <div class="payment-success-check"></div>
            </div>

            <h3 class="payment-success-title">Berhasil</h3>

            <p class="payment-success-text">
                Pembayaran sedang diproses...
            </p>
        </div>

    </div>
</div>

<script>
    let sedangSubmitPembayaran = false;

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    function hitungKembalian() {
        const total = parseInt(document.getElementById('total').value) || 0;
        const bayar = parseInt(document.getElementById('bayar').value) || 0;
        const kembalian = bayar - total;

        document.getElementById('kembalian').value = formatRupiah(kembalian >= 0 ? kembalian : 0);
    }

    function ubahMetodePembayaran() {
        const metode = document.getElementById('metode_pembayaran').value;
        const total = parseInt(document.getElementById('total').value) || 0;
        const bayar = document.getElementById('bayar');

        const info = document.getElementById('paymentMethodInfo');
        const iconWrap = document.getElementById('paymentMethodIconWrap');
        const icon = document.getElementById('paymentMethodIcon');
        const title = document.getElementById('paymentMethodTitle');
        const desc = document.getElementById('paymentMethodDesc');

        bayar.value = total;

        if (metode === 'qris') {
            bayar.readOnly = true;
            info.classList.remove('active-cash');
            info.classList.add('active-qris');
            iconWrap.style.background = '#60a5fa';
            icon.className = 'bi bi-qr-code';
            icon.style.color = '#1e3a5f';
            title.textContent = 'Pembayaran QRIS';
            desc.textContent = 'Nominal mengikuti total pesanan.';
        } else {
            bayar.readOnly = false;
            info.classList.remove('active-qris');
            info.classList.add('active-cash');
            iconWrap.style.background = 'rgba(34,197,94,0.15)';
            icon.className = 'bi bi-cash-coin';
            icon.style.color = '#16a34a';
            title.textContent = 'Pembayaran Cash';
            desc.textContent = 'Jumlah bayar bisa diubah jika uang lebih.';
        }

        hitungKembalian();
    }

    function openPaymentConfirmModal() {
        const form = document.getElementById('formPembayaran');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const total = parseInt(document.getElementById('total').value) || 0;
        const bayar = parseInt(document.getElementById('bayar').value) || 0;

        if (bayar < total) {
            alert('Jumlah bayar kurang dari total pembayaran.');
            return;
        }

        const modal = document.getElementById('paymentConfirmModal');
        const box = document.getElementById('paymentConfirmBox');

        box.classList.remove('success-mode');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closePaymentConfirmModal() {
        if (sedangSubmitPembayaran) {
            return;
        }

        const modal = document.getElementById('paymentConfirmModal');
        const box = document.getElementById('paymentConfirmBox');

        modal.classList.remove('show');
        box.classList.remove('success-mode');
        document.body.style.overflow = '';
    }

    function submitPembayaran() {
        if (sedangSubmitPembayaran) {
            return;
        }

        sedangSubmitPembayaran = true;

        const box = document.getElementById('paymentConfirmBox');
        const form = document.getElementById('formPembayaran');
        const btn = document.getElementById('btnSubmitPembayaran');

        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Memproses...';
        }

        box.classList.add('success-mode');

        setTimeout(function () {
            form.submit();
        }, 800);
    }

    document.addEventListener('DOMContentLoaded', function () {
        ubahMetodePembayaran();
        hitungKembalian();

        const modal = document.getElementById('paymentConfirmModal');

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePaymentConfirmModal();
                }
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePaymentConfirmModal();
        }
    });
</script>
@endsection