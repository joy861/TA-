<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #eef4ff;
            font-family: Arial, Helvetica, sans-serif;
            color: #0f2747;
            padding: 28px 16px;
        }

        .page { width: 100%; max-width: 920px; margin: 0 auto; }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .back-link {
            color: #1e3a5f;
            text-decoration: none;
            font-size: 14px;
            font-weight: 800;
        }

        .status-badge {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-badge.gray {
            background: #f1f5f9;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .card {
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 39, 71, 0.08);
            border: 1px solid rgba(30, 58, 95, 0.08);
        }

        .header {
            background: #1e3a5f;
            color: #ffffff;
            padding: 26px 30px;
        }

        .header-label {
            color: rgba(255,255,255,0.55);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .header-title { font-size: 30px; font-weight: 900; line-height: 1.2; }

        .header-desc {
            margin-top: 6px;
            font-size: 14px;
            color: rgba(255,255,255,0.72);
        }

        .content { padding: 28px 30px 30px; }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 14px;
            min-width: 0;
        }

        .info-label {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .info-value {
            color: #1e3a5f;
            font-size: 15px;
            font-weight: 900;
            word-break: break-word;
        }

        .alert-info {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .section {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            margin-top: 18px;
            background: #ffffff;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid #e2e8f0;
        }

        .section-title { color: #1e3a5f; font-size: 14px; font-weight: 900; }

        .section-note {
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            text-align: right;
        }

        .section-old { background: #f8fafc; border-color: #dbe3ef; }
        .section-old .section-head { background: #f1f5f9; }

        .section-new { background: #f0fdf4; border-color: #86efac; }
        .section-new .section-head { background: #dcfce7; border-bottom-color: #bbf7d0; }

        .section-add { background: #eff6ff; border-color: #bfdbfe; }
        .section-add .section-head { background: #dbeafe; border-bottom-color: #bfdbfe; }

        .item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 14px;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(30, 58, 95, 0.08);
        }

        .item:last-child { border-bottom: none; }

        .item-name {
            font-size: 16px;
            color: #0f2747;
            font-weight: 900;
            word-break: break-word;
        }

        .item-desc {
            margin-top: 4px;
            font-size: 12px;
            color: #64748b;
            font-weight: 700;
            line-height: 1.4;
        }

        .qty {
            min-width: 58px;
            text-align: center;
            border-radius: 12px;
            padding: 9px 12px;
            color: #ffffff;
            font-size: 18px;
            font-weight: 900;
        }

        .qty.gray { background: #94a3b8; }
        .qty.green { background: #16a34a; }
        .qty.blue { background: #2563eb; }

        .empty {
            margin-top: 18px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            padding: 24px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.5;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .btn-print {
            border: none;
            background: #0f2747;
            color: #ffffff;
            border-radius: 14px;
            padding: 14px 24px;
            min-width: 190px;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn-print:hover { background: #1e3a5f; }
        .btn-print:disabled { opacity: 0.65; cursor: not-allowed; }

        @media (max-width: 760px) {
            body { padding: 16px 10px; }
            .topbar { flex-direction: column; align-items: flex-start; }
            .status-badge { white-space: normal; }
            .content { padding: 20px 18px 22px; }
            .header { padding: 22px 20px; }
            .header-title { font-size: 25px; }
            .info-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .section-head { flex-direction: column; align-items: flex-start; }
            .section-note { text-align: left; }
            .actions { justify-content: stretch; }
            .btn-print { width: 100%; }
        }

        @media (max-width: 420px) {
            .info-grid { grid-template-columns: 1fr; }
            .item { grid-template-columns: 1fr; }
            .qty { justify-self: flex-start; }
        }
    </style>
</head>

<body>

@php
    $detailBaru = $detailBaru ?? collect();
    $detailTambahQty = $detailTambahQty ?? collect();
    $detailLama = $detailLama ?? collect();

    $jumlahUpdate = $detailBaru->count() + $detailTambahQty->count();
@endphp

<div class="page">
    <div class="topbar">
        <a href="{{ route('pesanan.index') }}" class="back-link">
            ← Kembali ke Data Pesanan
        </a>

        @if($jumlahUpdate > 0)
            <div class="status-badge">
                ● Ada {{ $jumlahUpdate }} update yang perlu dikirim ke dapur
            </div>
        @else
            <div class="status-badge gray">
                ● Tidak ada update baru
            </div>
        @endif
    </div>

    <div class="card">
        <div class="header">
            <div class="header-label">Detail Pesanan Dapur</div>
            <div class="header-title">Pesanan #{{ $pesanan->id_pesanan }}</div>
            <div class="header-desc">
                Ringkasan pesanan dibagi antara pesanan sebelumnya dan update baru.
            </div>
        </div>

        <div class="content">
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Meja</div>
                    <div class="info-value">Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Kasir</div>
                    <div class="info-value">{{ $pesanan->user->nama ?? '-' }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('d/m/Y') }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Waktu</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA
                    </div>
                </div>
            </div>

            <div class="alert-info">
                Pesanan lama ditampilkan sebagai <b>Pesanan Sebelumnya</b>.
                Menu tambahan setelah edit ditampilkan sebagai <b>Update Pesanan Baru</b> supaya kasir dan dapur tidak bingung.
            </div>

            @if($detailLama->count() > 0)
                <div class="section section-old">
                    <div class="section-head">
                        <div class="section-title">📌 PESANAN SEBELUMNYA</div>
                        <div class="section-note">Sudah pernah dikirim ke dapur</div>
                    </div>

                    @foreach($detailLama as $item)
                        <div class="item">
                            <div>
                                <div class="item-name">{{ $item->menu->nama_menu ?? '-' }}</div>
                                <div class="item-desc">
                                    Menu awal / menu lama pada pesanan ini.
                                    @if(!empty($item->tipe_harga))
                                        Tipe: {{ strtoupper($item->tipe_harga) }}
                                    @endif
                                </div>
                            </div>

                            <div class="qty gray">{{ $item->jumlah }}x</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($detailBaru->count() > 0)
                <div class="section section-new">
                    <div class="section-head">
                        <div class="section-title">🔥 UPDATE PESANAN BARU</div>
                        <div class="section-note">Perlu dimasak sekarang</div>
                    </div>

                    @foreach($detailBaru as $item)
                        <div class="item">
                            <div>
                                <div class="item-name">{{ $item->menu->nama_menu ?? '-' }}</div>
                                <div class="item-desc">
                                    Menu tambahan baru dari kasir dan perlu dikirim ke dapur.
                                    @if(!empty($item->tipe_harga))
                                        Tipe: {{ strtoupper($item->tipe_harga) }}
                                    @endif
                                </div>
                            </div>

                            <div class="qty green">{{ $item->jumlah }}x</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($detailTambahQty->count() > 0)
                <div class="section section-add">
                    <div class="section-head">
                        <div class="section-title">➕ TAMBAHAN PORSI</div>
                        <div class="section-note">Jumlah menu lama bertambah</div>
                    </div>

                    @foreach($detailTambahQty as $item)
                        @php
                            $jumlahAwal = (int) ($item->jumlah_awal ?? 0);
                            $jumlahSekarang = (int) ($item->jumlah ?? 0);
                            $jumlahTambah = max($jumlahSekarang - $jumlahAwal, 0);
                        @endphp

                        <div class="item">
                            <div>
                                <div class="item-name">{{ $item->menu->nama_menu ?? '-' }}</div>
                                <div class="item-desc">
                                    Sebelumnya {{ $jumlahAwal }}x, tambah {{ $jumlahTambah }}x, total sekarang {{ $jumlahSekarang }}x.
                                    @if(!empty($item->tipe_harga))
                                        Tipe: {{ strtoupper($item->tipe_harga) }}
                                    @endif
                                </div>
                            </div>

                            <div class="qty blue">+{{ $jumlahTambah }}x</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($detailLama->count() == 0 && $detailBaru->count() == 0 && $detailTambahQty->count() == 0)
                <div class="empty">
                    Belum ada detail pesanan.
                </div>
            @elseif($detailBaru->count() == 0 && $detailTambahQty->count() == 0)
                <div class="empty">
                    Tidak ada update pesanan baru yang perlu dimasak.<br>
                    Semua menu sudah pernah dikirim ke dapur.
                </div>
            @endif

            <div class="actions">
                <button type="button" class="btn-print" onclick="cetakKeDapur()">
                    Cetak ke Dapur
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let sedangProses = false;

    function cetakKeDapur() {
        if (sedangProses) return;

        sedangProses = true;

        const btn = document.querySelector('.btn-print');

        if (btn) {
            btn.textContent = 'Mengirim ke printer...';
            btn.disabled = true;
        }

        window.location.href = "{{ route('dapur.cetak', $pesanan->id_pesanan) }}";
    }
</script>

</body>
</html>
