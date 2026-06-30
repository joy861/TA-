<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    private function normalisasiFilter(?Request $request = null, ?string $tanggalRoute = null): array
    {
        $periode = $request?->input('periode', 'harian') ?? 'harian';

        $tanggalAkhirInput = $request?->input('tanggal_akhir')
            ?? $request?->input('tanggal')
            ?? $tanggalRoute
            ?? date('Y-m-d');

        $tanggalAkhir = Carbon::parse($tanggalAkhirInput)->toDateString();

        if ($periode === 'custom') {
            $tanggalAwal = Carbon::parse($request?->input('tanggal_awal') ?? $tanggalAkhir)->toDateString();
            $tanggalAkhir = Carbon::parse($request?->input('tanggal_akhir') ?? $tanggalAwal)->toDateString();

            if ($tanggalAwal > $tanggalAkhir) {
                [$tanggalAwal, $tanggalAkhir] = [$tanggalAkhir, $tanggalAwal];
            }
        } else {
            $akhir = Carbon::parse($tanggalAkhir);

            $tanggalAwal = match ($periode) {
                'tujuh_hari' => $akhir->copy()->subDays(6)->toDateString(),
                'satu_bulan' => $akhir->copy()->subMonthNoOverflow()->addDay()->toDateString(),
                'enam_bulan' => $akhir->copy()->subMonthsNoOverflow(6)->addDay()->toDateString(),
                'dua_belas_bulan' => $akhir->copy()->subMonthsNoOverflow(12)->addDay()->toDateString(),
                default => $akhir->toDateString(),
            };
        }

        return [
            'periode' => $periode,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'tanggal' => $tanggalAkhir,
            'periodeLabel' => $this->buatLabelPeriode($periode, $tanggalAwal, $tanggalAkhir),
        ];
    }

    private function buatLabelPeriode(string $periode, string $tanggalAwal, string $tanggalAkhir): string
    {
        $awal = Carbon::parse($tanggalAwal)->locale('id')->isoFormat('D MMMM YYYY');
        $akhir = Carbon::parse($tanggalAkhir)->locale('id')->isoFormat('D MMMM YYYY');

        if ($tanggalAwal === $tanggalAkhir) {
            return $awal;
        }

        return match ($periode) {
            'tujuh_hari' => "7 Hari Terakhir ($awal - $akhir)",
            'satu_bulan' => "1 Bulan Terakhir ($awal - $akhir)",
            'enam_bulan' => "6 Bulan Terakhir ($awal - $akhir)",
            'dua_belas_bulan' => "12 Bulan Terakhir ($awal - $akhir)",
            'custom' => "Custom ($awal - $akhir)",
            default => "$awal - $akhir",
        };
    }

    private function ambilDataLaporan(string $tanggalAwal, string $tanggalAkhir): array
    {
        $pesanan = Pesanan::whereDate('tanggal', '>=', $tanggalAwal)
            ->whereDate('tanggal', '<=', $tanggalAkhir)
            ->where('status', 'sudah_bayar')
            ->with('meja', 'user')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        $total = $pesanan->sum('total_harga');

        $menuTerlaris = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id_kategori')
            ->whereDate('pesanan.tanggal', '>=', $tanggalAwal)
            ->whereDate('pesanan.tanggal', '<=', $tanggalAkhir)
            ->where('pesanan.status', 'sudah_bayar')
            ->select(
                'menu.id_menu',
                'menu.nama_menu',
                'kategori.nama_kategori',
                DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'),
                DB::raw('SUM(COALESCE(detail_pesanan.subtotal, detail_pesanan.jumlah * detail_pesanan.harga_pakai, 0)) as total_pendapatan'),
                DB::raw('COUNT(DISTINCT pesanan.id_pesanan) as total_transaksi')
            )
            ->groupBy('menu.id_menu', 'menu.nama_menu', 'kategori.nama_kategori')
            ->orderByDesc('total_terjual')
            ->orderByDesc('total_pendapatan')
            ->get();

        return compact('pesanan', 'total', 'menuTerlaris');
    }

    public function index()
    {
        $filter = $this->normalisasiFilter();
        $activeTab = 'penjualan';

        return view('admin.laporan.index', array_merge(
            $this->ambilDataLaporan($filter['tanggalAwal'], $filter['tanggalAkhir']),
            $filter,
            compact('activeTab')
        ));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'periode' => 'nullable|in:harian,tujuh_hari,satu_bulan,enam_bulan,dua_belas_bulan,custom',
            'tanggal' => 'nullable|date',
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'active_tab' => 'nullable|in:penjualan,terlaris',
        ]);

        $filter = $this->normalisasiFilter($request);
        $activeTab = $request->active_tab ?? 'penjualan';

        return view('admin.laporan.index', array_merge(
            $this->ambilDataLaporan($filter['tanggalAwal'], $filter['tanggalAkhir']),
            $filter,
            compact('activeTab')
        ));
    }

    public function cetak(Request $request, $tanggal)
    {
        $filter = $this->normalisasiFilter($request, $tanggal);

        return view('admin.laporan.cetak', array_merge(
            $this->ambilDataLaporan($filter['tanggalAwal'], $filter['tanggalAkhir']),
            $filter
        ));
    }

    public function cetakMenuTerlaris(Request $request, $tanggal)
    {
        $filter = $this->normalisasiFilter($request, $tanggal);

        return view('admin.laporan.cetak_menu_terlaris', array_merge(
            $this->ambilDataLaporan($filter['tanggalAwal'], $filter['tanggalAkhir']),
            $filter
        ));
    }
    public function destroy($id)
{
    DB::beginTransaction();

    try {
        $pesanan = Pesanan::where('status', 'sudah_bayar')
            ->findOrFail($id);

        // Hapus detail pesanan dulu agar tidak error foreign key
        $pesanan->detailPesanan()->delete();

        // Hapus pesanan / transaksi lunas
        $pesanan->delete();

        DB::commit();

        return back()->with('success', 'Laporan transaksi lunas berhasil dihapus.');
    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
    }
}
}
