<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Services\ThermalPrinterService;
use Illuminate\Support\Facades\Log;

class DetailPesananController extends Controller
{
    public function dapur($id_pesanan)
    {
        $pesanan = Pesanan::with(['meja', 'user', 'detailPesanan.menu'])
            ->findOrFail($id_pesanan);

        $detailBaru = $pesanan->detailPesanan->where('is_new', 1);

        $detailTambahQty = $pesanan->detailPesanan
            ->where('is_new', 0)
            ->filter(fn ($d) => !is_null($d->jumlah_awal));

        $detailLama = $pesanan->detailPesanan
            ->where('is_new', 0)
            ->filter(fn ($d) => is_null($d->jumlah_awal));

        return view('kasir.pesanan.detail', compact(
            'pesanan',
            'detailBaru',
            'detailTambahQty',
            'detailLama'
        ));
    }

    public function cetakDapur($id_pesanan)
    {
        $pesanan = Pesanan::with(['meja', 'user', 'detailPesanan.menu'])
            ->findOrFail($id_pesanan);

        $detailBaru = $pesanan->detailPesanan->where('is_new', 1);

        $detailTambahQty = $pesanan->detailPesanan
            ->where('is_new', 0)
            ->filter(fn ($d) => !is_null($d->jumlah_awal));

        $detailLama = $pesanan->detailPesanan
            ->where('is_new', 0)
            ->filter(fn ($d) => is_null($d->jumlah_awal));

        /*
         * Logika cetak:
         * - Pesanan awal: belum ada pesanan lama, maka print pakai judul DAFTAR PESANAN.
         * - Update pesanan: sudah ada pesanan lama / tambahan qty, maka print dipisah:
         *   PESANAN SEBELUMNYA dan UPDATE PESANAN BARU.
         */
        $isUpdatePesanan = $detailLama->count() > 0 || $detailTambahQty->count() > 0;

        $itemsLama = collect();

        // Menu lama yang sudah pernah dikirim ke dapur
        foreach ($detailLama as $item) {
            $itemsLama->push([
                'nama'   => $item->menu->nama_menu ?? '-',
                'jumlah' => (int) ($item->jumlah ?? 0),
                'tipe'   => strtoupper($item->tipe_harga ?? 'NORMAL'),
            ]);
        }

        // Jika menu lama bertambah, tampilkan jumlah awalnya di PESANAN SEBELUMNYA
        foreach ($detailTambahQty as $item) {
            $jumlahAwal = (int) ($item->jumlah_awal ?? 0);

            if ($jumlahAwal > 0) {
                $itemsLama->push([
                    'nama'   => $item->menu->nama_menu ?? '-',
                    'jumlah' => $jumlahAwal,
                    'tipe'   => strtoupper($item->tipe_harga ?? 'NORMAL'),
                ]);
            }
        }

        $itemsBaru = collect();

        // Menu benar-benar baru setelah edit, atau menu awal saat pesanan pertama dibuat
        foreach ($detailBaru as $item) {
            $itemsBaru->push([
                'nama'   => $item->menu->nama_menu ?? '-',
                'jumlah' => (int) ($item->jumlah ?? 0),
                'tipe'   => strtoupper($item->tipe_harga ?? 'NORMAL'),
                'jenis'  => $isUpdatePesanan ? 'BARU' : null,
            ]);
        }

        // Tambahan porsi dari menu lama
        foreach ($detailTambahQty as $item) {
            $jumlahAwal = (int) ($item->jumlah_awal ?? 0);
            $jumlahSekarang = (int) ($item->jumlah ?? 0);
            $jumlahTambah = max($jumlahSekarang - $jumlahAwal, 0);

            if ($jumlahTambah > 0) {
                $itemsBaru->push([
                    'nama'   => $item->menu->nama_menu ?? '-',
                    'jumlah' => $jumlahTambah,
                    'tipe'   => strtoupper($item->tipe_harga ?? 'NORMAL'),
                    'jenis'  => 'TAMBAHAN',
                ]);
            }
        }

        if ($itemsBaru->count() === 0) {
            return redirect()->route('pesanan.index')
                ->with('success', 'Tidak ada pesanan baru yang perlu dicetak ke dapur.');
        }

        try {
            app(ThermalPrinterService::class)->cetakDapur([
                'tipe_cetak'   => $isUpdatePesanan ? 'update' : 'awal',
                'id_pesanan'   => $pesanan->id_pesanan,
                'nama_meja'    => $pesanan->meja->nomor_meja ?? '-',
                'waktu'        => now()->timezone('Asia/Makassar')->format('d/m/Y H:i'),
                'kasir'        => $pesanan->user->nama ?? '-',

                // Untuk format baru
                'pesanan_lama' => $itemsLama->toArray(),
                'pesanan_baru' => $itemsBaru->toArray(),

                // Backup kompatibilitas jika service lama masih memakai key detail
                'detail'       => $itemsBaru->toArray(),
            ]);

            // Setelah berhasil dicetak, semua item baru/tambahan dianggap sudah dikirim ke dapur
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
                ->with('success', 'Pesanan berhasil dicetak ke dapur.');

        } catch (\Exception $e) {
            Log::error('Cetak dapur gagal: ' . $e->getMessage());

            return redirect()->route('pesanan.index')
                ->with('error', 'Cetak dapur gagal: ' . $e->getMessage());
        }
    }

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
