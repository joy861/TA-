<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $pesanans = Pesanan::with('meja', 'user')
            ->whereDate('created_at', today()) // ✅ tambahkan ini
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kasir.pesanan.index', compact('pesanans'));
    }

    public function create()
    {
        $menu     = Menu::where('status', 'tersedia')->with('kategori')->get();
        $meja     = Meja::where('status', 'kosong')->get();
        $kategori = Kategori::all();

        return view('kasir.pesanan.create', compact('menu', 'meja', 'kategori'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $pesanan = Pesanan::create([
                'tanggal'     => date('Y-m-d'),
                'id_meja'     => $request->id_meja,
                'id_user'     => Auth::user()->id_user,
                'total_harga' => 0,
                'status'      => 'belum_bayar',
                'is_new'      => 0,
            ]);

            $total = 0;

            foreach ($request->menu as $key => $id_menu) {
                $menu     = Menu::findOrFail($id_menu);
                $jumlah   = $request->jumlah[$key];
                $subtotal = $menu->harga * $jumlah;

                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_menu'    => $id_menu,
                    'jumlah'     => $jumlah,
                    'subtotal'   => $subtotal,
                    'is_new'     => 0,
                ]);

                $total += $subtotal;
            }

            $pesanan->update(['total_harga' => $total]);

            Meja::where('id_meja', $request->id_meja)
                ->update(['status' => 'terisi']);

            DB::commit();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja')->findOrFail($id);
        return view('pesanan.show', compact('pesanan'));
    }

    public function edit($id)
    {
        $pesanan  = Pesanan::with('detailPesanan')->findOrFail($id);
        $menu     = Menu::all();
        $kategori = Kategori::all();
        $meja     = Meja::where('status', 'kosong')
                        ->orWhere('id_meja', $pesanan->id_meja)
                        ->get();

        return view('kasir.pesanan.edit', compact('pesanan', 'menu', 'meja', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $pesanan         = Pesanan::findOrFail($id);
            $oldMeja         = $pesanan->id_meja;
            $total           = 0;
            $existingDetails = DetailPesanan::where('id_pesanan', $id)->get();
            $processedIds    = [];

            foreach ($request->menu as $key => $id_menu) {
                $menu       = Menu::findOrFail($id_menu);
                $jumlahBaru = (int) $request->jumlah[$key];
                $id_detail  = $request->id_detail[$key] ?? null;

                $detail = null;
                if ($id_detail) {
                    $detail = $existingDetails->firstWhere('id_detail', $id_detail);
                }

                if ($detail) {
                    if ($detail->is_new == 0 && $jumlahBaru < $detail->jumlah) {
                        throw new \Exception("Jumlah tidak boleh dikurangi: " . $menu->nama_menu);
                    }

                    $detail->update([
                        'jumlah'      => $jumlahBaru,
                        'subtotal'    => $menu->harga * $jumlahBaru,
                        'jumlah_awal' => ($detail->is_new == 0 && $jumlahBaru > $detail->jumlah)
                            ? $detail->jumlah
                            : null,
                    ]);

                    $processedIds[] = $detail->id_detail;
                    $total += $menu->harga * $jumlahBaru;

                } else {
                    $new = DetailPesanan::create([
                        'id_pesanan'  => $id,
                        'id_menu'     => $id_menu,
                        'jumlah'      => $jumlahBaru,
                        'subtotal'    => $menu->harga * $jumlahBaru,
                        'is_new'      => 1,
                        'jumlah_awal' => null,
                    ]);

                    $processedIds[] = $new->id_detail;
                    $total += $menu->harga * $jumlahBaru;
                }
            }

            foreach ($existingDetails as $detail) {
                if (!in_array($detail->id_detail, $processedIds)) {
                    if ($detail->is_new == 0) {
                        throw new \Exception("Pesanan lama tidak boleh dihapus!");
                    }
                    $detail->delete();
                }
            }

            $pesanan->update([
                'id_meja'     => $request->id_meja,
                'total_harga' => $total,
            ]);

            if ($oldMeja != $request->id_meja) {
                Meja::where('id_meja', $oldMeja)->update(['status' => 'kosong']);
                Meja::where('id_meja', $request->id_meja)->update(['status' => 'terisi']);
            }

            DB::commit();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ✅ GET — Tampilkan halaman form pembayaran
    public function showBayar($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')->findOrFail($id);
        return view('kasir.transaksi.bayar', compact('pesanan'));
    }

    // ✅ POST — Proses pembayaran
    public function bayar(Request $request, $id)
{
    $request->validate([
        'metode_pembayaran' => 'required|in:cash,qris',
        'bayar'             => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status === 'sudah_bayar') {
            DB::rollBack();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan ini sudah dibayar.');
        }

        if ((int) $request->bayar < (int) $pesanan->total_harga) {
            DB::rollBack();

            return back()->with('error', 'Jumlah bayar kurang dari total pembayaran.');
        }

        $kembalian = (int) $request->bayar - (int) $pesanan->total_harga;

        $pesanan->update([
            'status'            => 'sudah_bayar',
            'metode_pembayaran' => $request->metode_pembayaran,
            'bayar'             => (int) $request->bayar,
            'kembalian'         => $kembalian,
        ]);

        Meja::where('id_meja', $pesanan->id_meja)
            ->update(['status' => 'kosong']);

        DB::commit();

// ✅ Ganti dengan ini
return redirect()->route('struk.show', $id)
    ->with('success', 'Pembayaran berhasil diproses!');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    public function detail($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($id);

        $detailBaru      = $pesanan->detailPesanan->where('is_new', 1);
        $detailTambahQty = $pesanan->detailPesanan->where('is_new', 0)->filter(fn($d) => !is_null($d->jumlah_awal));
        $detailLama      = $pesanan->detailPesanan->where('is_new', 0)->filter(fn($d) => is_null($d->jumlah_awal));

        return view('kasir.pesanan.detail', compact('pesanan', 'detailBaru', 'detailTambahQty', 'detailLama'));
    }
}