<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Meja;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Halaman pembayaran
    public function show($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja')
                    ->findOrFail($id);

        return view('kasir.transaksi.bayar', compact('pesanan'));
    }

    // Proses pembayaran
    public function proses(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'metode_pembayaran' => 'required|in:cash,qris',
                'bayar' => 'required|numeric|min:0',
            ]);

            $pesanan = Pesanan::findOrFail($id);

            if ($request->bayar < $pesanan->total_harga) {
                return back()->with('error', 'Jumlah bayar kurang dari total pembayaran.');
            }

            $pesanan->update([
                'status' => 'sudah_bayar',
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            Meja::where('id_meja', $pesanan->id_meja)
                ->update(['status' => 'kosong']);

            DB::commit();

            return redirect()->route('struk.show', $pesanan->id_pesanan)
                ->with('success', 'Pembayaran berhasil');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal pembayaran: ' . $e->getMessage());
        }
    }

    // Halaman struk (show)
    public function struk($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user') // ← tambah 'user'
                    ->findOrFail($id);

        return view('kasir.struk.show', compact('pesanan'));
    }

    // Halaman cetak struk ← METHOD BARU
    public function cetak($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
                    ->findOrFail($id);

        return view('kasir.struk.cetak', compact('pesanan'));
    }
}