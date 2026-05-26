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
        $query = Pesanan::with('meja', 'user')->orderBy('created_at', 'desc');

        // ── Resolve periode shortcut ──────────────────────────────
        $tanggalDari   = $request->tanggal_dari;
        $tanggalSampai = $request->tanggal_sampai;

        if ($request->periode === 'hari_ini') {
            $tanggalDari = $tanggalSampai = today()->format('Y-m-d');
        } elseif ($request->periode === 'kemarin') {
            $tanggalDari = $tanggalSampai = today()->subDay()->format('Y-m-d');
        } elseif ($request->periode === '7_hari') {
            $tanggalDari   = today()->subDays(6)->format('Y-m-d');
            $tanggalSampai = today()->format('Y-m-d');
        }

        // ── Default: kalau tidak ada filter tanggal sama sekali, tampilkan hari ini ──
        if (!$tanggalDari && !$tanggalSampai && !$request->periode) {
            $tanggalDari = $tanggalSampai = today()->format('Y-m-d');
        }

        // ── Terapkan filter tanggal ───────────────────────────────
        if ($tanggalDari) {
            $query->whereDate('created_at', '>=', $tanggalDari);
        }
        if ($tanggalSampai) {
            $query->whereDate('created_at', '<=', $tanggalSampai);
        }

        // ── Filter status ─────────────────────────────────────────
        $statusLunas = ['sudah_bayar', 'sudah bayar', 'lunas', 'selesai'];

        if ($request->filter === 'belum_bayar') {
            $query->whereNotIn('status', $statusLunas);
        } elseif ($request->filter === 'sudah_bayar') {
            $query->whereIn('status', $statusLunas);
        }

        // ── Filter pencarian meja ─────────────────────────────────
        if ($request->search) {
            $search = preg_replace('/[^0-9a-zA-Z]/', '', $request->search);
            $query->whereHas('meja', function ($q) use ($search) {
                $q->where('nomor_meja', 'like', "%{$search}%");
            });
        }

        $pesanans = $query->get();

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
                $menu       = Menu::findOrFail($id_menu);
                $jumlah     = (int) $request->jumlah[$key];
                $tipeHarga  = $request->tipe_harga[$key]  ?? 'normal';
                $hargaPakai = (int) ($request->harga_pakai[$key] ?? $menu->harga);
                $subtotal   = $hargaPakai * $jumlah;

                DetailPesanan::create([
                    'id_pesanan'  => $pesanan->id_pesanan,
                    'id_menu'     => $id_menu,
                    'jumlah'      => $jumlah,
                    'subtotal'    => $subtotal,
                    'tipe_harga'  => $tipeHarga,
                    'harga_pakai' => $hargaPakai,
                    'is_new'      => 0,
                ]);

                $total += $subtotal;
            }

            $pesanan->update(['total_harga' => $total]);

            Meja::where('id_meja', $request->id_meja)
                ->update(['status' => 'terisi']);

            DB::commit();

            return redirect()->route('pesanan.detail', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil disimpan dan siap dicetak!');

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
        // ── Validasi: menu tidak boleh kosong ─────────────────
        if (empty($request->menu)) {
            DB::rollBack();
            return back()->with('error', 'Pesanan tidak boleh kosong, minimal 1 menu harus dipilih.');
        }
            $pesanan         = Pesanan::findOrFail($id);
            $oldMeja         = $pesanan->id_meja;
            $total           = 0;
            $existingDetails = DetailPesanan::where('id_pesanan', $id)->get();
            $processedIds    = [];

            foreach ($request->menu as $key => $id_menu) {
                $menu       = Menu::findOrFail($id_menu);
                $jumlahBaru = (int) $request->jumlah[$key];
                $tipeHarga  = $request->tipe_harga[$key]  ?? 'normal';
                $hargaPakai = (int) ($request->harga_pakai[$key] ?? $menu->harga);
                $id_detail  = $request->id_detail[$key]   ?? null;

                $detail = null;
                if ($id_detail) {
                    $detail = $existingDetails->firstWhere('id_detail', $id_detail);
                }

                if ($detail) {
                    $detail->update([
                        'jumlah'      => $jumlahBaru,
                        'subtotal'    => $hargaPakai * $jumlahBaru,
                        'tipe_harga'  => $tipeHarga,
                        'harga_pakai' => $hargaPakai,
                        'jumlah_awal' => ($jumlahBaru != $detail->jumlah)
                            ? $detail->jumlah
                            : $detail->jumlah_awal,
                    ]);

                    $processedIds[] = $detail->id_detail;
                    $total          += $hargaPakai * $jumlahBaru;

                } else {
                    $new = DetailPesanan::create([
                        'id_pesanan'  => $id,
                        'id_menu'     => $id_menu,
                        'jumlah'      => $jumlahBaru,
                        'subtotal'    => $hargaPakai * $jumlahBaru,
                        'tipe_harga'  => $tipeHarga,
                        'harga_pakai' => $hargaPakai,
                        'is_new'      => 1,
                        'jumlah_awal' => null,
                    ]);

                    $processedIds[] = $new->id_detail;
                    $total          += $hargaPakai * $jumlahBaru;
                }
            }

            // Hapus detail yang tidak diproses
            foreach ($existingDetails as $detail) {
                if (!in_array($detail->id_detail, $processedIds)) {
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
            'metode_pembayaran' => 'required|in:cash,qris,card',
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