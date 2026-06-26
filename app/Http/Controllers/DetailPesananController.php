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

        $isUpdatePesanan = $detailLama->count() > 0 || $detailTambahQty->count() > 0;

        $itemsLama = collect();
        foreach ($detailLama as $item) {
            $itemsLama->push([
                'nama'    => $item->menu->nama_menu ?? '-',
                'jumlah'  => (int) ($item->jumlah ?? 0),
                'tipe'    => strtoupper($item->tipe_harga ?? 'NORMAL'),
                'catatan' => $item->catatan ?? null,
            ]);
        }
        foreach ($detailTambahQty as $item) {
            $jumlahAwal = (int) ($item->jumlah_awal ?? 0);
            if ($jumlahAwal > 0) {
                $itemsLama->push([
                    'nama'    => $item->menu->nama_menu ?? '-',
                    'jumlah'  => $jumlahAwal,
                    'tipe'    => strtoupper($item->tipe_harga ?? 'NORMAL'),
                    'catatan' => $item->catatan ?? null,
                ]);
            }
        }

        $itemsBaru = collect();
        foreach ($detailBaru as $item) {
            $itemsBaru->push([
                'nama'    => $item->menu->nama_menu ?? '-',
                'jumlah'  => (int) ($item->jumlah ?? 0),
                'tipe'    => strtoupper($item->tipe_harga ?? 'NORMAL'),
                'jenis'   => $isUpdatePesanan ? 'BARU' : null,
                'catatan' => $item->catatan ?? null,
            ]);
        }
        foreach ($detailTambahQty as $item) {
            $jumlahAwal     = (int) ($item->jumlah_awal ?? 0);
            $jumlahSekarang = (int) ($item->jumlah ?? 0);
            $jumlahTambah   = max($jumlahSekarang - $jumlahAwal, 0);
            if ($jumlahTambah > 0) {
                $itemsBaru->push([
                    'nama'    => $item->menu->nama_menu ?? '-',
                    'jumlah'  => $jumlahTambah,
                    'tipe'    => strtoupper($item->tipe_harga ?? 'NORMAL'),
                    'jenis'   => 'TAMBAHAN',
                    'catatan' => $item->catatan ?? null,
                ]);
            }
        }

if ($itemsBaru->count() === 0) {

    $itemsLama = collect();

    foreach ($pesanan->detailPesanan as $item) {
        $itemsLama->push([
            'nama'    => $item->menu->nama_menu ?? '-',
            'jumlah'  => (int) $item->jumlah,
            'tipe'    => strtoupper($item->tipe_harga ?? 'NORMAL'),
            'catatan' => $item->catatan ?? null,
        ]);
    }

    return view('kasir.struk.cetak_dapur', [
        'pesanan'         => $pesanan,
        'isUpdatePesanan' => false,
        'itemsLama'       => $itemsLama,
        'itemsBaru'       => collect(),
        'isReprint'       => true,
    ]);

        }

        // Tandai sudah dikirim ke dapur (karena cetak sekarang dilakukan di browser)
        DetailPesanan::where('id_pesanan', $id_pesanan)
            ->where(function ($q) {
                $q->where('is_new', 1)
                  ->orWhereNotNull('jumlah_awal');
            })
            ->update([
                'is_new'      => 0,
                'jumlah_awal' => null,
            ]);

        return view('kasir.struk.cetak_dapur', [
            'pesanan'         => $pesanan,
            'isUpdatePesanan' => $isUpdatePesanan,
            'itemsLama'       => $itemsLama,
            'itemsBaru'       => $itemsBaru,
        ]);
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
    public function reprint($id_pesanan)
{
    $pesanan = Pesanan::with(['meja','user','detailPesanan.menu'])
        ->findOrFail($id_pesanan);

    $itemsLama = collect();

    foreach ($pesanan->detailPesanan as $item) {
        $itemsLama->push([
            'nama'    => $item->menu->nama_menu ?? '-',
            'jumlah'  => (int) $item->jumlah,
            'tipe'    => strtoupper($item->tipe_harga ?? 'NORMAL'),
            'catatan' => $item->catatan ?? null,
        ]);
    }

    return view('kasir.struk.cetak_dapur', [
        'pesanan'         => $pesanan,
        'isUpdatePesanan' => false,
        'itemsLama'       => $itemsLama,
        'itemsBaru'       => collect(),
        'isReprint'       => true,
    ]);
}
}