@extends('layouts.admin')

@section('content')

{{-- PAGE TITLE --}}
<div class="mb-6">
    <h1 class="text-2xl font-black tracking-tight" style="color:#1e3a5f; letter-spacing:-0.5px;">Dashboard</h1>
    <p class="text-sm mt-0.5" style="color:rgba(30,58,95,0.5);">
        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
    </p>
</div>

{{-- BENTO GRID --}}
<div style="display:grid; grid-template-columns:2fr 1fr 1fr; grid-template-rows:auto auto; gap:12px; margin-bottom:12px;">

    {{-- Card Besar: Pendapatan --}}
    <div class="rounded-2xl p-6 flex flex-col justify-between" style="background:#1e3a5f; grid-row:1/3; min-height:220px;">
        <div>
            <div class="text-xs font-bold tracking-widest mb-1" style="color:rgba(255,255,255,0.4); letter-spacing:0.15em;">PENDAPATAN HARI INI</div>
            <div class="text-4xl font-black mt-2 leading-tight" style="color:#fff; letter-spacing:-1px;">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <div class="text-xs mt-2 font-semibold" style="color:#60a5fa;">↑ dari total keseluruhan</div>
        </div>
        <div>
            <div class="text-xs font-bold tracking-widest mb-3" style="color:rgba(255,255,255,0.3); letter-spacing:0.1em;">7 HARI TERAKHIR</div>
            <canvas id="miniChart" height="60"></canvas>
        </div>
    </div>

    {{-- Total Menu --}}
    <div class="rounded-2xl p-5 flex flex-col justify-between" style="background:#60a5fa; min-height:100px;">
        <div class="text-xs font-bold tracking-widest" style="color:rgba(30,58,95,0.7); letter-spacing:0.12em;">TOTAL MENU</div>
        <div>
            <div class="text-4xl font-black leading-tight" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalMenu }}</div>
            <div class="text-xs font-semibold mt-1" style="color:rgba(30,58,95,0.6);">item tersedia</div>
        </div>
    </div>

    {{-- Total Meja --}}
    <div class="rounded-2xl p-5 flex flex-col justify-between" style="background:#fff; border:1px solid rgba(30,58,95,0.08); min-height:100px;">
        <div class="text-xs font-bold tracking-widest" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">TOTAL MEJA</div>
        <div>
            <div class="text-4xl font-black leading-tight" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalMeja }}</div>
            <div class="text-xs font-semibold mt-1" style="color:rgba(30,58,95,0.4);">meja terdaftar</div>
        </div>
    </div>

    {{-- Total Pesanan --}}
    <div class="rounded-2xl p-5 flex flex-col justify-between" style="background:#fff; border:1px solid rgba(30,58,95,0.08); min-height:100px;">
        <div class="text-xs font-bold tracking-widest" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">TOTAL PESANAN</div>
        <div>
            <div class="text-4xl font-black leading-tight" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalPesanan }}</div>
            <div class="text-xs font-semibold mt-1" style="color:rgba(30,58,95,0.4);">transaksi tercatat</div>
        </div>
    </div>

    {{-- Status Donut --}}
    <div class="rounded-2xl p-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
        <div class="text-xs font-bold tracking-widest mb-3" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">STATUS</div>
        <div class="flex justify-center mb-3">
            <canvas id="statusChart" width="80" height="80"></canvas>
        </div>
        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 rounded-sm" style="background:#60a5fa;"></div>
                    <span style="color:rgba(30,58,95,0.6);">Selesai</span>
                </div>
                <span class="font-bold" style="color:#1e3a5f;">{{ $totalPesanan }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 rounded-sm" style="background:#e2e8f0;"></div>
                    <span style="color:rgba(30,58,95,0.6);">Pending</span>
                </div>
                <span class="font-bold" style="color:#1e3a5f;">0</span>
            </div>
        </div>
    </div>

</div>

{{-- TABEL PESANAN TERBARU --}}
<div class="rounded-2xl overflow-hidden" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
        <div>
            <h3 class="text-sm font-bold" style="color:#1e3a5f;">Pesanan Terbaru</h3>
            <p class="text-xs mt-0.5" style="color:rgba(30,58,95,0.4);">Transaksi terakhir masuk</p>
        </div>
        <a href="{{ route('laporan.index') }}"
           class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
           style="background:#eef2ff; color:#1e3a5f;"
           onmouseover="this.style.background='#1e3a5f';this.style.color='#fff'"
           onmouseout="this.style.background='#eef2ff';this.style.color='#1e3a5f'">
            Lihat Semua →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:rgba(30,58,95,0.02); border-bottom:1px solid rgba(30,58,95,0.06);">
                    <th class="text-left px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">#</th>
                    <th class="text-left px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Meja</th>
                    <th class="text-left px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Total</th>
                    <th class="text-left px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Waktu</th>
                    <th class="text-left px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananTerbaru ?? [] as $pesanan)
                <tr style="border-bottom:1px solid rgba(30,58,95,0.04);"
                    onmouseover="this.style.background='rgba(30,58,95,0.02)'"
                    onmouseout="this.style.background='transparent'">
                    <td class="px-6 py-3.5 text-xs font-mono" style="color:rgba(30,58,95,0.35);">#{{ $pesanan->id }}</td>
                    <td class="px-6 py-3.5 font-semibold" style="color:#1e3a5f;">Meja {{ $pesanan->meja->nomor_meja ?? '-' }}</td>
                    <td class="px-6 py-3.5 font-bold" style="color:#1e3a5f;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-xs" style="color:rgba(30,58,95,0.45);">{{ \Carbon\Carbon::parse($pesanan->created_at)->diffForHumans() }}</td>
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg"
                              style="background:#eef2ff; color:#1e3a5f;">Selesai</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm" style="color:rgba(30,58,95,0.3);">
                        <i class="bi bi-inbox text-3xl block mb-2"></i>
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('miniChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Sen','Sel','Rab','Kam','Jum','Sab','Min'],
            datasets: [{
                data: [0,0,0,0,0,0,{{ $totalPendapatan }}],
                borderColor: '#60a5fa',
                backgroundColor: 'rgba(96,165,250,0.12)',
                borderWidth: 2,
                pointBackgroundColor: '#60a5fa',
                pointBorderColor: '#1e3a5f',
                pointBorderWidth: 2,
                pointRadius: 3,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: {
                backgroundColor: '#fff', titleColor: '#1e3a5f', bodyColor: '#1e3a5f',
                borderColor: 'rgba(30,58,95,0.1)', borderWidth: 1, padding: 8, cornerRadius: 8,
                callbacks: { label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID') }
            }},
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 9, weight: '600' }, color: 'rgba(255,255,255,0.4)' }, border: { display: false } },
                y: { min: 0, grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { font: { size: 9 }, color: 'rgba(255,255,255,0.4)', callback: v => 'Rp'+(v/1000).toFixed(0)+'k' }, border: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            datasets: [{ data: [{{ $totalPesanan }}, 1], backgroundColor: ['#60a5fa','#e2e8f0'], borderWidth: 0 }]
        },
        options: { responsive: false, cutout: '72%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }
    });
</script>

@endsection