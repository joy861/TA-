<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Dapur - Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #eef2ff;
            color: #1e3a5f;
            padding: 24px 16px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header */
        .header-card {
            background: #1e3a5f;
            border-radius: 18px;
            padding: 24px;
            color: #fff;
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
        }

        .header-card::after {
            content: '';
            position: absolute;
            width: 180px; height: 180px;
            border-radius: 999px;
            background: rgba(96,165,250,0.15);
            right: -50px; top: -50px;
        }

        .header-eyebrow {
            font-size: 11px; font-weight: 800;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: #60a5fa;
            margin-bottom: 6px;
            position: relative; z-index: 2;
        }

        .header-title {
            font-size: 26px; font-weight: 900;
            letter-spacing: -0.5px; line-height: 1.1;
            margin-bottom: 12px;
            position: relative; z-index: 2;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            position: relative; z-index: 2;
        }

        .info-box {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 12px;
        }

        .info-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: rgba(255,255,255,0.55);
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px; font-weight: 800;
            color: #fff;
        }

        /* Section card */
        .section-card {
            background: #fff;
            border: 1px solid rgba(30,58,95,0.08);
            border-radius: 18px;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .section-header {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(30,58,95,0.06);
        }

        .section-header.baru { background: rgba(239,68,68,0.05); }
        .section-header.tambah { background: rgba(245,158,11,0.05); }
        .section-header.lama { background: rgba(30,58,95,0.02); }

        .section-icon {
            width: 32px; height: 32px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .section-icon.baru { background: #ef4444; color: #fff; }
        .section-icon.tambah { background: #f59e0b; color: #fff; }
        .section-icon.lama { background: rgba(30,58,95,0.15); color: #1e3a5f; }

        .section-title {
            font-size: 15px; font-weight: 900;
            color: #1e3a5f;
            letter-spacing: -0.2px;
        }

        .section-subtitle {
            font-size: 11px;
            color: rgba(30,58,95,0.5);
            font-weight: 600;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: rgba(30,58,95,0.02);
            color: rgba(30,58,95,0.4);
            font-size: 10px; font-weight: 800;
            letter-spacing: 0.1em; text-transform: uppercase;
            padding: 10px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(30,58,95,0.06);
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(30,58,95,0.04);
            font-size: 14px;
            color: #1e3a5f;
        }

        tr:last-child td { border-bottom: none; }

        .row-baru td { background: rgba(239,68,68,0.03); }
        .row-tambah td { background: rgba(245,158,11,0.03); }

        .menu-name {
            font-weight: 800;
            color: #1e3a5f;
        }

        .qty-pill {
            display: inline-flex; align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px; font-weight: 800;
            background: #eef2ff;
            color: #1e3a5f;
        }

        .qty-pill.bold-baru { background: #ef4444; color: #fff; }
        .qty-pill.bold-tambah { background: #f59e0b; color: #fff; }

        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 10px; font-weight: 800;
            white-space: nowrap;
        }

        .badge-baru { background: rgba(239,68,68,0.1); color: #b91c1c; }
        .badge-tambah { background: rgba(245,158,11,0.1); color: #b45309; }
        .badge-lama { background: rgba(30,58,95,0.08); color: rgba(30,58,95,0.6); }

        .badge::before {
            content: ''; width: 5px; height: 5px;
            border-radius: 50%; background: currentColor;
        }

        /* Empty success */
        .empty-success {
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 16px;
            padding: 22px;
            color: #15803d;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .empty-success-icon {
            width: 40px; height: 40px;
            background: #16a34a;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
        }

        /* Buttons */
        .btn-row {
            display: flex; gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            height: 44px; padding: 0 18px;
            border-radius: 12px;
            font-size: 13px; font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-back {
            background: #fff;
            color: #1e3a5f;
            border: 1.5px solid rgba(30,58,95,0.15);
        }
        .btn-back:hover { background: rgba(30,58,95,0.05); }

        .btn-print {
            background: #1e3a5f;
            color: #fff;
        }
        .btn-print:hover { background: #60a5fa; color: #1e3a5f; }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
            .header-title { font-size: 22px; }
        }

        @media print {
            body { background: #fff; padding: 16px; }
            .header-card { background: #1e3a5f !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .section-icon.baru { background: #ef4444 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .section-icon.tambah { background: #f59e0b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .qty-pill.bold-baru { background: #ef4444 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; color: #fff !important; }
            .qty-pill.bold-tambah { background: #f59e0b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; color: #fff !important; }
            .btn-back, .btn-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">

    {{-- HEADER --}}
    <div class="header-card">
        <div style="position:relative; z-index:2;">
            <div class="header-eyebrow">PESANAN UNTUK DAPUR</div>
            <div class="header-title">🍳 Tiket Pesanan Dapur</div>
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">ID Pesanan</div>
                    <div class="info-value">#{{ $pesanan->id_pesanan }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">No Meja</div>
                    <div class="info-value">{{ $pesanan->meja->nama_meja ?? 'Meja '.$pesanan->meja->nomor_meja }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Waktu</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- MENU BARU --}}
    @if($detailBaru->count() > 0)
        <div class="section-card">
            <div class="section-header baru">
                <div class="section-icon baru"><i class="bi bi-fire"></i></div>
                <div>
                    <div class="section-title">Menu Baru — Wajib Dimasak!</div>
                    <div class="section-subtitle">{{ $detailBaru->count() }} item baru perlu disiapkan</div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailBaru as $item)
                    <tr class="row-baru">
                        <td><span class="menu-name">{{ $item->menu->nama_menu }}</span></td>
                        <td><span class="qty-pill bold-baru">{{ $item->jumlah }}x</span></td>
                        <td><span class="badge badge-baru">Menu Baru</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- TAMBAH QTY --}}
    @if($detailTambahQty->count() > 0)
        <div class="section-card">
            <div class="section-header tambah">
                <div class="section-icon tambah"><i class="bi bi-plus-circle-fill"></i></div>
                <div>
                    <div class="section-title">Tambah Quantity — Masak Tambahannya!</div>
                    <div class="section-subtitle">{{ $detailTambahQty->count() }} item perlu ditambah porsi</div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Sudah Dimasak</th>
                        <th>Tambahan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailTambahQty as $item)
                    <tr class="row-tambah">
                        <td><span class="menu-name">{{ $item->menu->nama_menu }}</span></td>
                        <td style="color:rgba(30,58,95,0.5);">{{ $item->jumlah_awal }}x</td>
                        <td><span class="qty-pill bold-tambah">+{{ $item->jumlah - $item->jumlah_awal }}x</span></td>
                        <td><span class="qty-pill">{{ $item->jumlah }}x</span></td>
                        <td><span class="badge badge-tambah">Tambah Qty</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- MENU LAMA --}}
    @if($detailLama->count() > 0)
        <div class="section-card">
            <div class="section-header lama">
                <div class="section-icon lama"><i class="bi bi-clipboard-check"></i></div>
                <div>
                    <div class="section-title">Pesanan Lama — Sudah Dimasak</div>
                    <div class="section-subtitle">{{ $detailLama->count() }} item sebelumnya, tidak perlu dimasak ulang</div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailLama as $item)
                    <tr>
                        <td><span class="menu-name">{{ $item->menu->nama_menu }}</span></td>
                        <td><span class="qty-pill">{{ $item->jumlah }}x</span></td>
                        <td><span class="badge badge-lama">Tidak Ada Perubahan</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- EMPTY STATE --}}
    @if($detailBaru->count() == 0 && $detailTambahQty->count() == 0)
        <div class="empty-success">
            <div class="empty-success-icon"><i class="bi bi-check-lg"></i></div>
            <div>
                <div style="font-size:14px; font-weight:900;">Tidak ada pesanan baru untuk dimasak</div>
                <div style="font-size:12px; opacity:0.85;">Semua menu sudah disiapkan sebelumnya.</div>
            </div>
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="btn-row">
        <a href="{{ route('pesanan.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <button class="btn btn-print" onclick="printDapur()">
            <i class="bi bi-printer-fill"></i>
            <span>Cetak ke Dapur</span>
        </button>
    </div>
</div>

<form id="formSelesai" action="{{ route('dapur.selesai', $pesanan->id_pesanan) }}" method="POST">
    @csrf
</form>

<script>
    let sudahKirim = false;

    function submitDanRedirect() {
        if (sudahKirim) return;
        sudahKirim = true;
        let form = document.getElementById('formSelesai');
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            }
        }).then(() => {
            window.location.href = "{{ route('pesanan.index') }}";
        });
    }

    function printDapur() {
        window.print();
    }

    window.onafterprint = function () {
        submitDanRedirect();
    };
</script>

</body>
</html>