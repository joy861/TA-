@extends('layouts.admin')

@section('content')

@php
    $activeTab = $activeTab ?? 'penjualan';
    $menuTerlaris = $menuTerlaris ?? collect();

    $periode = $periode ?? 'harian';
    $tanggalAwal = $tanggalAwal ?? ($tanggal ?? date('Y-m-d'));
    $tanggalAkhir = $tanggalAkhir ?? ($tanggal ?? date('Y-m-d'));
    $periodeLabel = $periodeLabel ?? $tanggalAkhir;

    $periodeText = [
        'harian' => 'Hari Ini / Harian',
        'tujuh_hari' => '7 Hari Terakhir',
        'satu_bulan' => '1 Bulan',
        'enam_bulan' => '6 Bulan',
        'dua_belas_bulan' => '12 Bulan',
        'custom' => 'Custom',
    ][$periode] ?? 'Harian';

    $queryCetak = http_build_query([
        'periode' => $periode,
        'tanggal_awal' => $tanggalAwal,
        'tanggal_akhir' => $tanggalAkhir,
    ]);

    $totalTransaksi = $pesanan->count();

    $totalTunai  = $pesanan->where('metode_pembayaran', 'cash')->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07))));
    $jumlahTunai = $pesanan->where('metode_pembayaran', 'cash')->count();

    $totalQris   = $pesanan->where('metode_pembayaran', 'qris')->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07))));
    $jumlahQris  = $pesanan->where('metode_pembayaran', 'qris')->count();

    $totalCard   = $pesanan->where('metode_pembayaran', 'card')->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07)) + ($p->biaya_card ?? 0)));
    $jumlahCard  = $pesanan->where('metode_pembayaran', 'card')->count();

    $totalPendapatan = $pesanan->sum(fn($p) => $p->total_bayar ?? ($p->total_harga + ($p->pajak ?? round($p->total_harga * 0.07)) + ($p->biaya_card ?? 0)));

    $totalItemTerjual = $menuTerlaris->sum('total_terjual');
    $totalPendapatanMenu = $menuTerlaris->sum('total_pendapatan');
    $menuPalingLaris = $menuTerlaris->first();
@endphp

<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
    <div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1" style="color:#60a5fa; letter-spacing:0.15em;">ANALISIS</p>
        <h1 class="text-2xl font-black tracking-tight" style="color:#1e3a5f; letter-spacing:-0.5px;">Laporan</h1>
        <p class="text-sm mt-0.5" style="color:rgba(30,58,95,0.5);">Rekap penjualan dan Produk terlaris restoran</p>
    </div>

    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('laporan.cetak', $tanggalAkhir) }}?{{ $queryCetak }}" target="_blank"
           data-print-btn="penjualan"
           class="laporan-print-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex-shrink-0"
           style="background:#1e3a5f; color:#fff;">
            <i class="bi bi-printer"></i> Cetak Penjualan
        </a>

        <a href="{{ route('laporan.menu-terlaris.cetak', $tanggalAkhir) }}?{{ $queryCetak }}" target="_blank"
           data-print-btn="terlaris"
           class="laporan-print-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex-shrink-0"
           style="background:#1e3a5f; color:#fff; display:none;">
            <i class="bi bi-printer"></i> Cetak Produk Terlaris
        </a>
    </div>
</div>

{{-- TAB LAPORAN --}}
<div class="rounded-2xl p-2 mb-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <button type="button" data-report-tab="penjualan" onclick="showReportTab('penjualan')"
                class="laporan-tab-btn inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-black transition-all"
                style="border:none; background:#1e3a5f; color:#fff;">
            <i class="bi bi-cash-stack"></i>
            Laporan Penjualan
        </button>

        <button type="button" data-report-tab="terlaris" onclick="showReportTab('terlaris')"
                class="laporan-tab-btn inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-black transition-all"
                style="border:none; background:#eef2ff; color:#1e3a5f;">
            <i class="bi bi-trophy"></i>
            Produk Terlaris
        </button>
    </div>
</div>

{{-- FILTER --}}
<div class="rounded-2xl p-5 mb-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <form action="{{ route('laporan.filter') }}" method="POST" id="formLaporan">
        @csrf

        <input type="hidden" name="active_tab" id="active_tab" value="{{ $activeTab }}">
        <input type="hidden" name="periode" id="periode" value="{{ $periode }}">

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase mb-1" style="color:#60a5fa; letter-spacing:0.15em;">FILTER PERIODE</p>
                <p class="text-xs font-semibold" style="color:rgba(30,58,95,0.45);">
                    Periode aktif: <span style="color:#1e3a5f;">{{ $periodeText }}</span> · {{ $periodeLabel }}
                </p>
            </div>

            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold"
                  style="background:#eef2ff; color:#1e3a5f;">
                <i class="bi bi-calendar-range"></i> {{ $periodeText }}
            </span>
        </div>

        <div class="flex flex-wrap gap-2 mb-4">
            <button type="button" onclick="setPeriode('harian')" data-periode="harian" class="periode-btn px-3 py-2 rounded-xl text-xs font-bold transition-all">Hari Ini</button>
            <button type="button" onclick="setPeriode('tujuh_hari')" data-periode="tujuh_hari" class="periode-btn px-3 py-2 rounded-xl text-xs font-bold transition-all">7 Hari</button>
            <button type="button" onclick="setPeriode('satu_bulan')" data-periode="satu_bulan" class="periode-btn px-3 py-2 rounded-xl text-xs font-bold transition-all">1 Bulan</button>
            <button type="button" onclick="setPeriode('enam_bulan')" data-periode="enam_bulan" class="periode-btn px-3 py-2 rounded-xl text-xs font-bold transition-all">6 Bulan</button>
            <button type="button" onclick="setPeriode('dua_belas_bulan')" data-periode="dua_belas_bulan" class="periode-btn px-3 py-2 rounded-xl text-xs font-bold transition-all">12 Bulan</button>
            <button type="button" onclick="setPeriode('custom')" data-periode="custom" class="periode-btn px-3 py-2 rounded-xl text-xs font-bold transition-all">Custom</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1fr_1fr_auto] gap-3">
            <div>
                <label class="block text-xs font-bold tracking-widest uppercase mb-2" style="color:rgba(30,58,95,0.45);">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}"
                       class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                       style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;">
            </div>

            <div>
                <label class="block text-xs font-bold tracking-widest uppercase mb-2" style="color:rgba(30,58,95,0.45);">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}"
                       class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                       style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;">
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all"
                        style="background:#1e3a5f; color:#fff; border:none;">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </div>
        </div>
    </form>
</div>

{{-- PANEL LAPORAN PENJUALAN --}}
<div id="panel-penjualan" class="laporan-panel">
    {{-- BENTO STATS --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
        <div class="rounded-2xl p-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
            <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">TOTAL TRANSAKSI</div>
            <div class="text-4xl font-black" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalTransaksi }}</div>
            <div class="text-xs mt-1 font-semibold" style="color:rgba(30,58,95,0.4);">{{ $periodeText }}</div>
        </div>

        <div class="rounded-2xl p-5" style="background:#1e3a5f;">
            <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(255,255,255,0.4); letter-spacing:0.12em;">TOTAL PENDAPATAN</div>
            <div class="font-black leading-tight" style="color:#fff; letter-spacing:-1px;">
                <span class="text-sm font-bold" style="color:rgba(255,255,255,0.5);">Rp </span>
                <span class="text-3xl">{{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
            <div class="text-xs mt-1 font-semibold" style="color:rgba(255,255,255,0.4);">sudah termasuk pajak &amp; service</div>
        </div>
    </div>

    {{-- BREAKDOWN --}}
    <div class="rounded-2xl p-5 mb-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
        <h3 class="text-sm font-black mb-0.5" style="color:#1e3a5f;">Breakdown Pembayaran</h3>
        <p class="text-xs mb-4" style="color:rgba(30,58,95,0.4);">Pendapatan berdasarkan metode pembayaran (sudah termasuk service)</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl p-4" style="border:1.5px solid rgba(30,58,95,0.08); background:#eef2ff;">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black" style="background:#1e3a5f; color:#60a5fa;">Rp</div>
                    <span class="text-sm font-black" style="color:#1e3a5f;">Tunai</span>
                </div>
                <div class="text-2xl font-black mb-1" style="color:#1e3a5f;">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
                <div class="text-xs font-semibold" style="color:rgba(30,58,95,0.5);">{{ $jumlahTunai }} transaksi</div>
            </div>

            <div class="rounded-xl p-4" style="border:1.5px solid rgba(30,58,95,0.08); background:#eef2ff;">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black" style="background:#60a5fa; color:#1e3a5f;">QR</div>
                    <span class="text-sm font-black" style="color:#1e3a5f;">QRIS</span>
                </div>
                <div class="text-2xl font-black mb-1" style="color:#1e3a5f;">Rp {{ number_format($totalQris, 0, ',', '.') }}</div>
                <div class="text-xs font-semibold" style="color:rgba(30,58,95,0.5);">{{ $jumlahQris }} transaksi</div>
            </div>

            <div class="rounded-xl p-4" style="border:1.5px solid rgba(30,58,95,0.08); background:#eef2ff;">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black" style="background:rgba(99,102,241,0.15); color:#6366f1;">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>
                    <span class="text-sm font-black" style="color:#1e3a5f;">Card</span>
                </div>
                <div class="text-2xl font-black mb-1" style="color:#1e3a5f;">Rp {{ number_format($totalCard, 0, ',', '.') }}</div>
                <div class="text-xs font-semibold" style="color:rgba(30,58,95,0.5);">{{ $jumlahCard }} transaksi</div>
            </div>
        </div>
    </div>

    {{-- TABEL DETAIL TRANSAKSI --}}
    <div class="rounded-2xl overflow-hidden" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
            <div>
                <h3 class="text-sm font-black" style="color:#1e3a5f;">Detail Transaksi</h3>
                <p class="text-xs mt-0.5" style="color:rgba(30,58,95,0.4);">{{ $periodeLabel }}</p>
            </div>

            <span class="text-xs font-bold px-3 py-1.5 rounded-lg" style="background:#eef2ff; color:#1e3a5f;">
                {{ $totalTransaksi }} transaksi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:rgba(30,58,95,0.02); border-bottom:1px solid rgba(30,58,95,0.06);">
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase w-12" style="color:rgba(30,58,95,0.35);">No</th>
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Tanggal</th>
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Meja</th>
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Kasir</th>
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Metode</th>
                        <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Subtotal</th>
                        <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Service</th>
                        <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Total</th>
                        <th class="text-center px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pesanan as $p)
                        @php
                            $pSubtotal  = $p->total_harga ?? 0;
                            $pPajak     = $p->pajak ?? round($pSubtotal * 0.07);
                            $pBiayaCard = $p->biaya_card ?? 0;
                            $pTotal     = $p->total_bayar ?? ($pSubtotal + $pPajak + $pBiayaCard);

                            $isSplit = in_array($p->metode_pembayaran, ['qris', 'card'])
                                && (($p->bayar_cash ?? 0) > 0)
                                && (($p->bayar_elektronik ?? 0) > 0);
                        @endphp

                        <tr style="border-bottom:1px solid rgba(30,58,95,0.04);">
                            <td class="px-5 py-3.5 text-xs" style="color:rgba(30,58,95,0.35);">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-5 py-3.5 text-xs font-semibold" style="color:rgba(30,58,95,0.5);">
                                {{ $p->tanggal }}
                            </td>

                            <td class="px-5 py-3.5 font-black" style="color:#1e3a5f;">
                                Meja {{ $p->meja->nomor_meja ?? '-' }}
                            </td>

                            <td class="px-5 py-3.5 font-semibold" style="color:rgba(30,58,95,0.6);">
                                {{ $p->user->nama ?? '-' }}
                            </td>

                            <td class="px-5 py-3.5">
                                @if($isSplit && $p->metode_pembayaran == 'qris')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full" style="background:#eef2ff; color:#1e3a5f;">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background:#60a5fa;"></span>
                                        Split Cash + QRIS
                                    </span>
                                @elseif($isSplit && $p->metode_pembayaran == 'card')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full" style="background:rgba(99,102,241,0.1); color:#6366f1;">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background:#6366f1;"></span>
                                        Split Cash + Card
                                    </span>
                                @elseif($p->metode_pembayaran == 'cash')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full" style="background:rgba(34,197,94,0.1); color:#15803d;">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background:#22c55e;"></span>
                                        Tunai
                                    </span>
                                @elseif($p->metode_pembayaran == 'qris')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full" style="background:#eef2ff; color:#1e3a5f;">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background:#60a5fa;"></span>
                                        QRIS
                                    </span>
                                @elseif($p->metode_pembayaran == 'card')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full" style="background:rgba(99,102,241,0.1); color:#6366f1;">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background:#6366f1;"></span>
                                        Card
                                    </span>
                                @else
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eef2ff; color:rgba(30,58,95,0.5);">
                                        -
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-3.5 text-right font-semibold" style="color:rgba(30,58,95,0.6);">
                                Rp {{ number_format($pSubtotal, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 text-right font-semibold" style="color:rgba(30,58,95,0.6);">
                                Rp {{ number_format($pPajak + $pBiayaCard, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 text-right font-black" style="color:#1e3a5f;">
                                Rp {{ number_format($pTotal, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 text-center">
                                <form action="{{ route('laporan.destroy', $p->id_pesanan) }}"
                                      method="POST"
                                      class="form-hapus-laporan">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold transition-all"
                                            style="background:#fff1f2; color:#dc2626; border:none; cursor:pointer;"
                                            onmouseover="this.style.background='#dc2626'; this.style.color='#fff';"
                                            onmouseout="this.style.background='#fff1f2'; this.style.color='#dc2626';">
                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-sm" style="color:rgba(30,58,95,0.3);">
                                <i class="bi bi-calendar-x text-3xl block mb-2"></i>
                                Tidak ada {{ $periodeText }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($pesanan->count() > 0)
                    <tfoot>
                        <tr style="background:#eef2ff; border-top:2px solid rgba(30,58,95,0.1);">
                            <td colspan="5" class="px-5 py-3.5 text-sm font-black" style="color:#1e3a5f;">
                                Total Keseluruhan
                            </td>

                            <td class="px-5 py-3.5 text-right font-semibold text-sm" style="color:rgba(30,58,95,0.6);">
                                Rp {{ number_format($pesanan->sum('total_harga'), 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 text-right font-semibold text-sm" style="color:rgba(30,58,95,0.6);">
                                Rp {{ number_format($pesanan->sum(fn($p) => ($p->pajak ?? round($p->total_harga * 0.07)) + ($p->biaya_card ?? 0)), 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 text-right font-black text-base" style="color:#1e3a5f;">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- PANEL PRODUK TERLARIS --}}
<div id="panel-terlaris" class="laporan-panel" style="display:none;">
    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:12px;">
        <div class="rounded-2xl p-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
            <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">JUMLAH MENU TERJUAL</div>
            <div class="text-4xl font-black" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalItemTerjual }}</div>
            <div class="text-xs mt-1 font-semibold" style="color:rgba(30,58,95,0.4);">total porsi/item terjual</div>
        </div>

        <div class="rounded-2xl p-5" style="background:#60a5fa;">
            <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.55); letter-spacing:0.12em;">MENU PALING LARIS</div>
            <div class="text-2xl font-black leading-tight" style="color:#1e3a5f; letter-spacing:-0.5px;">{{ $menuPalingLaris->nama_menu ?? '-' }}</div>
            <div class="text-xs mt-2 font-semibold" style="color:rgba(30,58,95,0.6);">
                {{ $menuPalingLaris ? number_format($menuPalingLaris->total_terjual, 0, ',', '.') . ' terjual' : 'belum ada transaksi' }}
            </div>
        </div>

        <div class="rounded-2xl p-5" style="background:#1e3a5f;">
            <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(255,255,255,0.4); letter-spacing:0.12em;">PENDAPATAN MENU</div>
            <div class="font-black leading-tight" style="color:#fff; letter-spacing:-1px;">
                <span class="text-sm font-bold" style="color:rgba(255,255,255,0.5);">Rp </span>
                <span class="text-3xl">{{ number_format($totalPendapatanMenu, 0, ',', '.') }}</span>
            </div>
            <div class="text-xs mt-1 font-semibold" style="color:rgba(255,255,255,0.4);">subtotal dari menu terjual</div>
        </div>
    </div>

    <div class="rounded-2xl overflow-hidden" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
            <div>
                <h3 class="text-sm font-black" style="color:#1e3a5f;">Ranking Produk Terlaris</h3>
                <p class="text-xs mt-0.5" style="color:rgba(30,58,95,0.4);">Berdasarkan transaksi sudah bayar pada {{ $periodeLabel }}</p>
            </div>

            <span class="text-xs font-bold px-3 py-1.5 rounded-lg" style="background:#eef2ff; color:#1e3a5f;">{{ $menuTerlaris->count() }} menu</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:rgba(30,58,95,0.02); border-bottom:1px solid rgba(30,58,95,0.06);">
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase w-12" style="color:rgba(30,58,95,0.35);">Rank</th>
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Nama Menu</th>
                        <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Kategori</th>
                        <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Jumlah Terjual</th>
                        <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Transaksi</th>
                        <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Total Pendapatan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($menuTerlaris as $item)
                        <tr style="border-bottom:1px solid rgba(30,58,95,0.04);">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-xs font-black"
                                      style="background:{{ $loop->iteration <= 3 ? '#1e3a5f' : '#eef2ff' }}; color:{{ $loop->iteration <= 3 ? '#fff' : '#1e3a5f' }};">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 font-black" style="color:#1e3a5f;">{{ $item->nama_menu }}</td>
                            <td class="px-5 py-3.5 font-semibold" style="color:rgba(30,58,95,0.55);">{{ $item->nama_kategori ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-right font-black" style="color:#1e3a5f;">{{ number_format($item->total_terjual, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold" style="color:rgba(30,58,95,0.6);">{{ number_format($item->total_transaksi, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right font-black" style="color:#1e3a5f;">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm" style="color:rgba(30,58,95,0.3);">
                                <i class="bi bi-trophy text-3xl block mb-2"></i>
                                Belum ada data menu terjual pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($menuTerlaris->count() > 0)
                    <tfoot>
                        <tr style="background:#eef2ff; border-top:2px solid rgba(30,58,95,0.1);">
                            <td colspan="3" class="px-5 py-3.5 text-sm font-black" style="color:#1e3a5f;">Total Keseluruhan</td>
                            <td class="px-5 py-3.5 text-right font-black text-sm" style="color:#1e3a5f;">{{ number_format($totalItemTerjual, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold text-sm" style="color:rgba(30,58,95,0.6);">-</td>
                            <td class="px-5 py-3.5 text-right font-black text-base" style="color:#1e3a5f;">Rp {{ number_format($totalPendapatanMenu, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showReportTab(tab) {
        const panels = document.querySelectorAll('.laporan-panel');
        const buttons = document.querySelectorAll('.laporan-tab-btn');
        const printButtons = document.querySelectorAll('.laporan-print-btn');
        const activeInput = document.getElementById('active_tab');

        panels.forEach(panel => {
            panel.style.display = panel.id === `panel-${tab}` ? 'block' : 'none';
        });

        buttons.forEach(btn => {
            const isActive = btn.dataset.reportTab === tab;
            btn.style.background = isActive ? '#1e3a5f' : '#eef2ff';
            btn.style.color = isActive ? '#fff' : '#1e3a5f';
        });

        printButtons.forEach(btn => {
            btn.style.display = btn.dataset.printBtn === tab ? 'inline-flex' : 'none';
        });

        if (activeInput) activeInput.value = tab;
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function setPeriode(periode) {
        const periodeInput = document.getElementById('periode');
        const awalInput = document.getElementById('tanggal_awal');
        const akhirInput = document.getElementById('tanggal_akhir');

        periodeInput.value = periode;

        const today = new Date();
        let start = new Date(today);
        let end = new Date(today);

        if (periode === 'tujuh_hari') {
            start.setDate(today.getDate() - 6);
        } else if (periode === 'satu_bulan') {
            start.setMonth(today.getMonth() - 1);
            start.setDate(start.getDate() + 1);
        } else if (periode === 'enam_bulan') {
            start.setMonth(today.getMonth() - 6);
            start.setDate(start.getDate() + 1);
        } else if (periode === 'dua_belas_bulan') {
            start.setMonth(today.getMonth() - 12);
            start.setDate(start.getDate() + 1);
        } else if (periode === 'custom') {
            awalInput.focus();
            updatePeriodeButton();
            return;
        }

        awalInput.value = formatDate(start);
        akhirInput.value = formatDate(end);

        updatePeriodeButton();
    }

    function updatePeriodeButton() {
        const periode = document.getElementById('periode')?.value || 'harian';

        document.querySelectorAll('.periode-btn').forEach(btn => {
            const isActive = btn.dataset.periode === periode;

            btn.style.background = isActive ? '#1e3a5f' : '#eef2ff';
            btn.style.color = isActive ? '#fff' : '#1e3a5f';
            btn.style.border = 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        showReportTab(@json($activeTab));
        updatePeriodeButton();

        const awalInput = document.getElementById('tanggal_awal');
        const akhirInput = document.getElementById('tanggal_akhir');
        const periodeInput = document.getElementById('periode');

        [awalInput, akhirInput].forEach(input => {
            input?.addEventListener('change', function () {
                if (periodeInput) periodeInput.value = 'custom';
                updatePeriodeButton();
            });
        });

        document.querySelectorAll('.form-hapus-laporan').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Hapus Laporan?',
                    text: 'Data transaksi lunas dan detail pesanan akan ikut terhapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonColor: '#1e3a5f'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal',
                text: @json(session('error')),
                icon: 'error',
                confirmButtonColor: '#dc2626'
            });
        @endif
    });
</script>

@endsection