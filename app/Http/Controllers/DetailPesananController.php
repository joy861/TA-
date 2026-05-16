<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;

class DetailPesananController extends Controller
{
    // 🔥 Tampilkan semua pesanan ke dapur (lama, baru, tambah qty)
    public function dapur($id_pesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.menu'])
            ->findOrFail($id_pesanan);

        // Menu baru ditambahkan kasir
        $detailBaru = $pesanan->detailPesanan
            ->where('is_new', 1);

        // Menu lama yang quantity-nya bertambah
        $detailTambahQty = $pesanan->detailPesanan
            ->where('is_new', 0)
            ->filter(fn($d) => !is_null($d->jumlah_awal));

        // Menu lama tidak ada perubahan
        $detailLama = $pesanan->detailPesanan
            ->where('is_new', 0)
            ->filter(fn($d) => is_null($d->jumlah_awal));

        return view('dapur.index', compact(
            'pesanan',
            'detailBaru',
            'detailTambahQty',
            'detailLama'
        ));
    }

    // ✅ Tandai sudah dimasak — reset semua flag
    public function selesai($id_pesanan)
    {
        DetailPesanan::where('id_pesanan', $id_pesanan)
            ->where(function ($q) {
                $q->where('is_new', 1)
                  ->orWhereNotNull('jumlah_awal');
            })
            ->update([
                'is_new'      => 0,
                'jumlah_awal' => null,
            ]);

        return redirect()->route('pesanan.index')
            ->with('success', 'Pesanan sudah diproses dapur');
    }
}