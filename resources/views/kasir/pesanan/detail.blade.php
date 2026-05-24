<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dapur - Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 76mm; /* pas untuk thermal 80mm */
            margin: 0 auto;
            padding: 4mm 3mm;
            background: #fff;
            color: #000;
            font-size: 11px;
            line-height: 1.4;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }

        .header-title {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header-sub {
            font-size: 10px;
            margin-top: 2px;
        }

        /* Info baris */
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding: 1.5px 0;
        }

        .info-label { font-weight: normal; }
        .info-value { font-weight: 700; text-align: right; }

        .info-block {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }

        /* Section */
        .section-title {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 0;
            margin-bottom: 4px;
            text-align: center;
        }

        .section-title.baru {
            border-top: 3px solid #000;
            border-bottom: 3px solid #000;
        }

        /* Item baris */
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 3px 0;
            border-bottom: 1px dotted #aaa;
            gap: 4px;
        }

        .item-row:last-child { border-bottom: none; }

        .item-name {
            font-weight: 700;
            font-size: 12px;
            flex: 1;
        }

        .item-qty {
            font-weight: 900;
            font-size: 13px;
            white-space: nowrap;
            text-align: right;
        }

        .item-qty.baru { font-size: 14px; }

        .item-note {
            font-size: 10px;
            color: #444;
            margin-top: 1px;
        }

        .tambah-detail {
            font-size: 10px;
            display: flex;
            justify-content: space-between;
            color: #333;
            padding-top: 2px;
        }

        .section-gap { margin-bottom: 6px; }

        /* Asterisk marker untuk item baru */
        .marker-baru { margin-right: 3px; }

        /* Empty state */
        .empty-state {
            text-align: center;
            font-size: 11px;
            padding: 6px 0;
            border: 1px dashed #000;
            margin-bottom: 6px;
        }

        /* Footer */
        .footer {
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 5px;
            margin-top: 6px;
            font-size: 10px;
        }

        /* Print only */
        @media screen {
            body {
                border: 1px dashed #ccc;
                padding: 12px;
                margin: 20px auto;
            }
            .btn-row {
                display: flex;
                gap: 8px;
                margin-top: 16px;
                justify-content: center;
            }
            .btn {
                font-family: 'Courier New', monospace;
                font-size: 12px;
                font-weight: 700;
                padding: 8px 16px;
                border: 2px solid #000;
                background: #fff;
                cursor: pointer;
                text-decoration: none;
                color: #000;
            }
            .btn-print { background: #000; color: #fff; }
        }

        @media print {
            body { border: none; margin: 0; padding: 4mm 2mm; width: 76mm; }
            .btn-row { display: none !important; }
            @page { margin: 0; size: 80mm auto; }
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-title">*** DAPUR ***</div>
        <div class="header-sub">PANDE HILL — Garden View</div>
    </div>

    {{-- INFO --}}
    <div class="info-block">
        <div class="info-row">
            <span class="info-label">No. Pesanan</span>
            <span class="info-value">#{{ $pesanan->id_pesanan }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Meja</span>
            <span class="info-value">
                {{ $pesanan->meja->nama_meja ?? 'Meja '.$pesanan->meja->nomor_meja }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Waktu</span>
            <span class="info-value">
                {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    {{-- MENU BARU --}}
    @if($detailBaru->count() > 0)
        <div class="section-gap">
            <div class="section-title baru">!! MASAK SEKARANG !!</div>
            @foreach($detailBaru as $item)
            <div class="item-row">
                <div class="item-name">
                    <span class="marker-baru">▶</span>{{ $item->menu->nama_menu }}
                </div>
                <div class="item-qty baru">{{ $item->jumlah }}x</div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- TAMBAH QTY --}}
    @if($detailTambahQty->count() > 0)
        <div class="section-gap">
            <div class="section-title">+ TAMBAH PORSI</div>
            @foreach($detailTambahQty as $item)
            <div class="item-row">
                <div style="flex:1;">
                    <div class="item-name">{{ $item->menu->nama_menu }}</div>
                    <div class="tambah-detail">
                        <span>Sudah: {{ $item->jumlah_awal }}x</span>
                        <span>Tambah: +{{ $item->jumlah - $item->jumlah_awal }}x</span>
                        <span>Total: {{ $item->jumlah }}x</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- MENU LAMA --}}
    @if($detailLama->count() > 0)
        <div class="section-gap">
            <div class="section-title">Sudah Dimasak</div>
            @foreach($detailLama as $item)
            <div class="item-row">
                <div class="item-name" style="color:#555;">{{ $item->menu->nama_menu }}</div>
                <div class="item-qty" style="color:#555;">{{ $item->jumlah }}x</div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- EMPTY STATE --}}
    @if($detailBaru->count() == 0 && $detailTambahQty->count() == 0)
        <div class="empty-state">
            Tidak ada pesanan baru.<br>Semua sudah dimasak.
        </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <div>— Selesaikan dengan cepat! —</div>
        <div style="margin-top:2px;">{{ \Carbon\Carbon::now()->timezone('Asia/Makassar')->format('H:i') }} WIT</div>
    </div>

    {{-- TOMBOL (hanya tampil di layar, tidak ikut print) --}}
    <div class="btn-row">
        <a href="{{ route('pesanan.index') }}" class="btn">← Kembali</a>
        <button type="button" class="btn btn-print" onclick="printDapur()">Cetak ke Dapur</button>
    </div>

</body>

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
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.href = "{{ route('pesanan.index') }}";
        });
    }

    async function printDapur() {
        if (sudahKirim) return;
        sudahKirim = true;

        let btn = document.querySelector('.btn-print');
        btn.textContent = 'Mengirim ke printer...';
        btn.disabled = true;

        try {
            let response = await fetch("{{ route('dapur.cetak', $pesanan->id_pesanan) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'Accept': 'application/json'
                }
            });

            let result = await response.json().catch(() => null);
            if (!response.ok) {
                throw new Error(result?.message || 'Gagal mengirim ke printer');
            }

            submitDanRedirect();
        } catch (error) {
            sudahKirim = false;
            btn.textContent = 'Cetak ke Dapur';
            btn.disabled = false;
            alert(error.message || 'Terjadi kesalahan saat mengirim printer.');
        }
    }

</script>

</html>