<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Meja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,qris,card',
            'bayar'             => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $pesanan = Pesanan::findOrFail($id);

            $subtotal   = $pesanan->total_harga;
            $pajak      = round($subtotal * 0.07);
            $biayaCard  = $request->metode_pembayaran === 'card'
                            ? round(($subtotal + $pajak) * 0.02)
                            : 0;
            $totalBayar = $subtotal + $pajak + $biayaCard;

            if ($request->metode_pembayaran !== 'card' && $request->bayar < $totalBayar) {
                DB::rollBack();
                return back()->with('error', 'Jumlah bayar kurang dari total pembayaran.');
            }

            $bayar     = $request->metode_pembayaran === 'card' ? $totalBayar : $request->bayar;
            $kembalian = $bayar - $totalBayar;

            $pesanan->update([
                'status'            => 'sudah_bayar',
                'metode_pembayaran' => $request->metode_pembayaran,
                'bayar'             => $bayar,
                'kembalian'         => $kembalian,
                'pajak'             => $pajak,
                'biaya_card'        => $biayaCard,
                'total_bayar'       => $totalBayar,
            ]);

            Meja::where('id_meja', $pesanan->id_meja)
                ->update(['status' => 'kosong']);

            DB::commit();

            // Cetak dilakukan manual dari halaman struk via window.print()
            return redirect()->route('struk.cetak', $pesanan->id_pesanan)
                ->with('success', 'Pembayaran berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal pembayaran: ' . $e->getMessage());
        }
    }

    // Halaman struk (show)
    public function struk($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
                    ->findOrFail($id);

        return view('kasir.struk.show', compact('pesanan'));
    }

    // Halaman cetak struk
    public function cetak($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
                    ->findOrFail($id);

        return view('kasir.struk.cetak', compact('pesanan'));
    }
}