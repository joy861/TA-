<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Services\ThermalPrinterService;
use Illuminate\Support\Facades\Log;

class StrukController extends Controller
{
    public function show($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
            ->findOrFail($id);

        return view('kasir.struk.show', compact('pesanan'));
    }

    public function cetak($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
            ->findOrFail($id);

        return view('kasir.struk.cetak', compact('pesanan'));
    }

    public function cetakThermal($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
            ->findOrFail($id);

        try {
            app(ThermalPrinterService::class)->cetakStruk([
                'id_pesanan' => $pesanan->id_pesanan,
                'nama_meja'  => $pesanan->meja->nomor_meja ?? '-',
                'waktu'      => $pesanan->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i'),
                'kasir'      => $pesanan->user->nama ?? '-',
                'metode'     => strtoupper($pesanan->metode_pembayaran ?? 'CASH'),
                'total'      => $pesanan->total_harga,
                'bayar'      => $pesanan->bayar ?? $pesanan->total_harga,
                'kembalian'  => $pesanan->kembalian ?? 0,
                'detail'     => $pesanan->detailPesanan->map(function ($item) {
                    $harga = $item->harga_pakai ?? $item->menu->harga ?? 0;

                    return [
                        'nama'     => $item->menu->nama_menu ?? '-',
                        'jumlah'   => $item->jumlah ?? 0,
                        'harga'    => $harga,
                        'subtotal' => $item->subtotal ?? ($harga * $item->jumlah),
                    ];
                })->toArray(),
            ]);

            return redirect()->route('struk.show', $pesanan->id_pesanan)
                ->with('success', 'Struk telah dikirim ke printer thermal.');

        } catch (\Exception $e) {
            Log::error('Cetak struk thermal gagal: ' . $e->getMessage());

            return redirect()->route('struk.show', $pesanan->id_pesanan)
                ->with('error', 'Cetak struk gagal: ' . $e->getMessage());
        }
    }
}