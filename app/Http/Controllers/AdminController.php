<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMenu       = \App\Models\Menu::count();
        $totalMeja       = \App\Models\Meja::count();
        $totalPesanan    = \App\Models\Pesanan::count();
        $totalPendapatan = \App\Models\Pesanan::where('status', 'sudah_bayar')->sum('total_harga');
        $pesananTerbaru  = \App\Models\Pesanan::with('meja')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMenu',
            'totalMeja',
            'totalPesanan',
            'totalPendapatan',
            'pesananTerbaru'
        ));
    }
}