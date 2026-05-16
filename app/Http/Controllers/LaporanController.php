<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class LaporanController extends Controller
{
    // Laporan hari ini
    public function index()
    {
        $tanggal = date('Y-m-d');

        $pesanan = Pesanan::whereDate('tanggal', $tanggal)
                    ->where('status', 'sudah_bayar')
                    ->with('meja', 'user')
                    ->get();

        $total = $pesanan->sum('total_harga');

        return view('admin.laporan.index', compact('pesanan', 'total', 'tanggal'));
    }

    // Filter laporan berdasarkan tanggal
    public function filter(Request $request)
    {
        $tanggal = $request->tanggal;

        $pesanan = Pesanan::whereDate('tanggal', $tanggal)
                    ->where('status', 'sudah_bayar')
                    ->with('meja', 'user')
                    ->get();

        $total = $pesanan->sum('total_harga');

        return view('admin.laporan.index', compact('pesanan', 'total', 'tanggal'));
    }

    // Cetak laporan (sederhana)
    public function cetak($tanggal)
    {
        $pesanan = Pesanan::whereDate('tanggal', $tanggal)
                    ->where('status', 'sudah_bayar')
                    ->with('meja', 'user')
                    ->get();

        $total = $pesanan->sum('total_harga');

        return view('admin.laporan.cetak', compact('pesanan', 'total', 'tanggal'));
    }
}