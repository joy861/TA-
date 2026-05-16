<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Meja;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function dashboard()
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        $statusSudahBayar = [
            'sudah_bayar',
            'sudah bayar',
            'lunas',
            'selesai',
        ];

        $statusBelumBayar = [
            'belum_bayar',
            'belum bayar',
            'pending',
        ];

        // Total semua pesanan hari ini
        $totalPesananHariIni = Pesanan::whereDate('tanggal', $tanggalHariIni)
            ->count();

        // Pesanan yang belum bayar, dihitung semua yang masih aktif
        $pesananBelumBayar = Pesanan::whereIn('status', $statusBelumBayar)
            ->count();

        // Pendapatan hari ini hanya dari pesanan yang sudah dibayar
        $pendapatanHariIni = Pesanan::whereDate('tanggal', $tanggalHariIni)
            ->whereIn('status', $statusSudahBayar)
            ->sum('total_harga');

        // Meja aktif dihitung dari pesanan yang belum bayar
        $mejaAktifDariPesanan = Pesanan::whereIn('status', $statusBelumBayar)
            ->whereNotNull('id_meja')
            ->distinct('id_meja')
            ->count('id_meja');

        // Cadangan: kalau status meja juga dipakai
        $mejaAktifDariStatus = Meja::where('status', 'terisi')
            ->count();

        $mejaAktif = max($mejaAktifDariPesanan, $mejaAktifDariStatus);

        // Pesanan terbaru hari ini
        $pesananTerbaru = Pesanan::with('meja')
            ->whereDate('tanggal', $tanggalHariIni)
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'totalPesananHariIni',
            'pesananBelumBayar',
            'pendapatanHariIni',
            'mejaAktif',
            'pesananTerbaru'
        ));
    }
}