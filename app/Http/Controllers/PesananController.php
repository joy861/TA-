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
use App\Services\ThermalPrinterService;
use Illuminate\Support\Facades\Log;

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
if ($request->filled('search')) {
    $searchAsli = trim($request->search);

    // Ambil angka dari input.
    // Contoh:
    // "meja 2"  => "2"
    // "Meja 10" => "10"
    // "2"       => "2"
    preg_match('/\d+/', $searchAsli, $angka);

    $nomorMeja = $angka[0] ?? $searchAsli;

    $query->whereHas('meja', function ($q) use ($nomorMeja) {
        $q->where('nomor_meja', 'like', "%{$nomorMeja}%");
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
    $request->validate([
        'id_meja' => 'required',
        'menu'    => 'required|array|min:1',
        'jumlah'  => 'required|array|min:1',
    ], [
        'id_meja.required' => 'Meja wajib dipilih.',
        'menu.required'    => 'Minimal pilih 1 menu.',
        'jumlah.required'  => 'Jumlah menu wajib diisi.',
    ]);

    DB::beginTransaction();

    try {
        $pesanan = Pesanan::create([
    'tanggal'     => date('Y-m-d'),
    'id_meja'     => $request->id_meja,
    'id_user'     => Auth::user()->id_user,
    'total_harga' => 0,
    'status'      => 'belum_bayar',
]);

        $total = 0;

        foreach ($request->menu as $key => $id_menu) {
            $menu       = Menu::findOrFail($id_menu);
            $jumlah     = (int) ($request->jumlah[$key] ?? 1);
            $tipeHarga  = $request->tipe_harga[$key] ?? 'normal';
            $hargaPakai = (int) ($request->harga_pakai[$key] ?? $menu->harga);
            $subtotal   = $hargaPakai * $jumlah;

            DetailPesanan::create([
                'id_pesanan'  => $pesanan->id_pesanan,
                'id_menu'     => $id_menu,
                'jumlah'      => $jumlah,
                'subtotal'    => $subtotal,
                'tipe_harga'  => $tipeHarga,
                'harga_pakai' => $hargaPakai,
                'is_new'      => 1,
                'jumlah_awal' => null,
            ]);

            $total += $subtotal;
        }

        $pesanan->update([
            'total_harga' => $total,
        ]);

        Meja::where('id_meja', $request->id_meja)
            ->update(['status' => 'terisi']);

        DB::commit();

        return redirect()->route('dapur.cetak', $pesanan->id_pesanan)
            ->with('success', 'Pesanan berhasil disimpan dan siap dicetak ke dapur.');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
    $request->validate([
        'id_meja' => 'required',
        'menu'    => 'required|array|min:1',
        'jumlah'  => 'required|array|min:1',
    ], [
        'id_meja.required' => 'Meja wajib dipilih.',
        'menu.required'    => 'Pesanan tidak boleh kosong, minimal 1 menu harus dipilih.',
        'jumlah.required'  => 'Jumlah menu wajib diisi.',
    ]);

    DB::beginTransaction();

    try {
        $pesanan = Pesanan::findOrFail($id);
        $oldMeja = $pesanan->id_meja;

        $existingDetails = DetailPesanan::where('id_pesanan', $id)->get();
        $processedIds = [];
        $total = 0;

        foreach ($request->menu as $key => $id_menu) {
            $menu = Menu::findOrFail($id_menu);

            $jumlahBaru = (int) ($request->jumlah[$key] ?? 1);
            $tipeHarga = $request->tipe_harga[$key] ?? 'normal';
            $hargaPakai = (int) ($request->harga_pakai[$key] ?? $menu->harga);
            $subtotal = $hargaPakai * $jumlahBaru;

            /*
             * Penting:
             * id_detail[] harus sejajar dengan menu[], jumlah[], harga_pakai[], tipe_harga[].
             * File edit.blade.php yang baru sudah mengirim id_detail[] sesuai menu yang dipilih.
             * Menu baru wajib mengirim id_detail kosong, supaya tidak dianggap mengganti menu lama.
             */
            $idDetail = $request->id_detail[$key] ?? null;
            $detail = null;

            if (!empty($idDetail)) {
                $detail = $existingDetails->firstWhere('id_detail', (int) $idDetail);
            }

            if ($detail) {
                $jumlahLama = (int) ($detail->jumlah ?? 0);
                $menuLama = (int) ($detail->id_menu ?? 0);
                $isNewLama = (int) ($detail->is_new ?? 0);

                $menuBerubah = $menuLama !== (int) $id_menu;
                $jumlahBertambah = $jumlahBaru > $jumlahLama;

                if ($menuBerubah) {
                    /*
                     * Kalau baris lama berubah menu, anggap sebagai update baru untuk dapur.
                     * Ini pengaman jika browser/DOM pernah mengirim id_detail yang tidak cocok.
                     */
                    $isNew = 1;
                    $jumlahAwal = null;
                } elseif ($isNewLama === 0 && $jumlahBertambah) {
                    /*
                     * Menu lama ditambah qty:
                     * yang dikirim ke dapur hanya tambahan porsinya.
                     */
                    $isNew = 0;
                    $jumlahAwal = $jumlahLama;
                } elseif ($isNewLama === 1) {
                    /*
                     * Menu baru yang belum sempat dicetak ke dapur tetap menu baru.
                     */
                    $isNew = 1;
                    $jumlahAwal = null;
                } else {
                    /*
                     * Menu lama tidak berubah / jumlah berkurang:
                     * tidak perlu dikirim ulang ke dapur.
                     */
                    $isNew = 0;
                    $jumlahAwal = null;
                }

                $detail->update([
                    'id_menu'     => $id_menu,
                    'jumlah'      => $jumlahBaru,
                    'subtotal'    => $subtotal,
                    'tipe_harga'  => $tipeHarga,
                    'harga_pakai' => $hargaPakai,
                    'is_new'      => $isNew,
                    'jumlah_awal' => $jumlahAwal,
                ]);

                $processedIds[] = $detail->id_detail;
            } else {
                /*
                 * Ini menu yang benar-benar baru ditambahkan saat edit.
                 * Contoh: awal Ayam Goreng + Ayam Geprek,
                 * lalu edit tambah Matcha 2x.
                 * Maka Matcha masuk is_new = 1.
                 */
                $new = DetailPesanan::create([
                    'id_pesanan'  => $id,
                    'id_menu'     => $id_menu,
                    'jumlah'      => $jumlahBaru,
                    'subtotal'    => $subtotal,
                    'tipe_harga'  => $tipeHarga,
                    'harga_pakai' => $hargaPakai,
                    'is_new'      => 1,
                    'jumlah_awal' => null,
                ]);

                $processedIds[] = $new->id_detail;
            }

            $total += $subtotal;
        }

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
            ->with('success', 'Pesanan berhasil diupdate.');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', 'Gagal update pesanan: ' . $e->getMessage());
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

        // Subtotal dari total harga pesanan
        $subtotal = (int) ($pesanan->total_harga ?? 0);

        // Service / pajak 7%
        $pajak = (int) round($subtotal * 0.07);

        // Biaya card 2%, hanya kalau metode pembayaran card
        $biayaCard = 0;

        if ($request->metode_pembayaran === 'card') {
            $biayaCard = (int) round(($subtotal + $pajak) * 0.02);
        }

        // Total akhir yang harus dibayar
        $totalBayar = $subtotal + $pajak + $biayaCard;

        // Validasi jumlah bayar
        if ((int) $request->bayar < $totalBayar) {
            DB::rollBack();

            return back()->with(
                'error',
                'Jumlah bayar kurang. Total yang harus dibayar adalah Rp ' . number_format($totalBayar, 0, ',', '.')
            );
        }

        // Kembalian dihitung dari total akhir
        $kembalian = (int) $request->bayar - $totalBayar;

        // Simpan pembayaran ke database
        $pesanan->update([
            'status'            => 'sudah_bayar',
            'metode_pembayaran' => $request->metode_pembayaran,

            // Ini yang dibutuhkan laporan admin
            'pajak'             => $pajak,
            'biaya_card'        => $biayaCard,
            'total_bayar'       => $totalBayar,

            // Data pembayaran
            'bayar'             => (int) $request->bayar,
            'kembalian'         => $kembalian,
        ]);

        // Meja kembali kosong setelah dibayar
        Meja::where('id_meja', $pesanan->id_meja)
            ->update(['status' => 'kosong']);

        DB::commit();

        // Ambil ulang data lengkap untuk cetak struk
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
            ->findOrFail($id);

        try {
            app(ThermalPrinterService::class)->cetakStruk([
                'id_pesanan' => $pesanan->id_pesanan,
                'nama_meja'  => $pesanan->meja->nomor_meja ?? '-',
                'waktu'      => $pesanan->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i'),
                'kasir'      => $pesanan->user->nama ?? '-',
                'metode'     => strtoupper($pesanan->metode_pembayaran ?? 'CASH'),

                // Total pembayaran final
                'total'      => $pesanan->total_bayar ?? $pesanan->total_harga,
                'bayar'      => $pesanan->bayar ?? $pesanan->total_bayar,
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

            return redirect()->route('pesanan.index')
                ->with('success', 'Pembayaran berhasil diproses dan struk berhasil dicetak.');

        } catch (\Exception $e) {
            Log::error('Cetak struk setelah bayar gagal: ' . $e->getMessage());

            return redirect()->route('pesanan.index')
                ->with('error', 'Pembayaran berhasil, tetapi cetak struk gagal: ' . $e->getMessage());
        }

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    public function detail($id)
{
    $pesanan = Pesanan::with(['meja', 'user', 'detailPesanan.menu'])
        ->findOrFail($id);

    $detailBaru = $pesanan->detailPesanan
        ->where('is_new', 1);

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
}