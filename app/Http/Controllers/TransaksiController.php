<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Meja;
use App\Services\ThermalPrinterService;
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
        DB::beginTransaction();

        try {
                            dd($request->all()); // ← tambah ini dulu, lihat apa yang masuk
            $request->validate([
                'metode_pembayaran' => 'required|in:cash,qris,card',
                'bayar' => 'required|numeric|min:0',
            ]);

            $pesanan = Pesanan::findOrFail($id);

            $subtotal     = $pesanan->total_harga;
            $pajak        = round($subtotal * 0.07);
            $biayaCard    = $request->metode_pembayaran === 'card' ? round(($subtotal + $pajak) * 0.02) : 0;
            $totalBayar   = $subtotal + $pajak + $biayaCard;

            if ($request->metode_pembayaran !== 'card' && $request->bayar < $totalBayar) {
                return back()->with('error', 'Jumlah bayar kurang dari total pembayaran.');
            }

            $bayar     = $request->metode_pembayaran === 'card' ? $totalBayar : $request->bayar;
            $kembalian = $bayar - $totalBayar;

            $pesanan->update([
                'status'             => 'sudah_bayar',
                'metode_pembayaran'  => $request->metode_pembayaran,
                'bayar'              => $bayar,
                'kembalian'          => $kembalian,
                'pajak'              => $pajak,
                'biaya_card'         => $biayaCard,
                'total_bayar'        => $totalBayar,
            ]);

            Meja::where('id_meja', $pesanan->id_meja)
                ->update(['status' => 'kosong']);

            DB::commit();

            try {
                (new ThermalPrinterService())->cetakStruk([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'nama_meja'  => $pesanan->meja->nomor_meja ?? '-',
                    'waktu'      => $pesanan->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i'),
                    'kasir'      => $pesanan->user->nama ?? '-',
                    'metode'     => strtoupper($pesanan->metode_pembayaran),
                    'subtotal'   => $subtotal,
                    'pajak'      => $pajak,
                    'biaya_card' => $biayaCard,
                    'total'      => $totalBayar,
                    'bayar'      => $pesanan->bayar,
                    'kembalian'  => $pesanan->kembalian,
                    'detail'     => $pesanan->detailPesanan->map(function ($item) {
                        return [
                            'nama'     => $item->menu->nama_menu ?? '-',
                            'jumlah'   => $item->jumlah,
                            'harga'    => $item->menu->harga ?? 0,
                            'subtotal' => $item->subtotal ?? ($item->menu->harga * $item->jumlah),
                        ];
                    })->toArray(),
                ]);

                return redirect()->route('struk.show', $pesanan->id_pesanan)
                    ->with('success', 'Pembayaran berhasil dan struk sudah dicetak.');
            } catch (\Exception $e) {
                Log::error('Cetak struk gagal: ' . $e->getMessage());

                return redirect()->route('struk.show', $pesanan->id_pesanan)
                    ->with('success', 'Pembayaran berhasil.')
                    ->with('error', 'Cetak struk gagal: ' . $e->getMessage());
            }

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