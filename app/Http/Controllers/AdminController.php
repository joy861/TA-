<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Meja;
use App\Models\Pesanan;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        // Total menu yang tersedia
        $totalMenu = Menu::where('status', 'tersedia')->count();

        // Total meja terdaftar
        $totalMeja = Meja::count();

        // Semua pesanan hari ini
        $pesananHariIni = Pesanan::with(['meja', 'user'])
            ->whereDate('tanggal', $tanggalHariIni)
            ->latest()
            ->get();

        // Total pesanan hari ini
        $totalPesanan = $pesananHariIni->count();

        // Status pesanan hari ini
        $totalSelesai = $pesananHariIni->where('status', 'sudah_bayar')->count();
        $totalPending = $pesananHariIni->where('status', 'belum_bayar')->count();

        // Pendapatan hari ini dari pesanan yang sudah dibayar
        $totalPendapatan = $pesananHariIni
            ->where('status', 'sudah_bayar')
            ->sum(function ($pesanan) {
                $subtotal = (int) ($pesanan->total_harga ?? 0);
                $service = (int) ($pesanan->pajak ?? 0) + (int) ($pesanan->biaya_card ?? 0);
                $totalFinal = (int) ($pesanan->total_bayar ?? 0);

                return $totalFinal > 0 ? $totalFinal : ($subtotal + $service);
            });

        // Pesanan terbaru hari ini saja
        $pesananTerbaru = $pesananHariIni->take(5);

        return view('admin.dashboard', compact(
            'totalMenu',
            'totalMeja',
            'totalPesanan',
            'totalPendapatan',
            'pesananTerbaru',
            'totalSelesai',
            'totalPending'
        ));
    }
}