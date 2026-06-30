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
    $kategori = Kategori::all();

    $meja = Meja::all()->map(function ($m) {
        $terisi = Pesanan::where('id_meja', $m->id_meja)
            ->where('status', 'belum_bayar')
            ->sum('jumlah_orang');

        $m->terisi = $terisi;
        $m->sisa   = max($m->kapasitas - $terisi, 0);

        return $m;
    })->filter(fn ($m) => $m->sisa > 0)->values();

    return view('kasir.pesanan.create', compact('menu', 'meja', 'kategori'));
}

public function store(Request $request)
{
    $request->validate([
        'id_meja'      => 'required',
        'jumlah_orang' => 'required|integer|min:1',
        'menu'         => 'required|array|min:1',
        'jumlah'       => 'required|array|min:1',
    ], [
        'id_meja.required'      => 'Meja wajib dipilih.',
        'jumlah_orang.required' => 'Jumlah orang wajib diisi.',
        'jumlah_orang.min'      => 'Jumlah orang minimal 1.',
        'menu.required'         => 'Minimal pilih 1 menu.',
        'jumlah.required'       => 'Jumlah menu wajib diisi.',
    ]);

    DB::beginTransaction();

    try {
        $meja = Meja::lockForUpdate()->findOrFail($request->id_meja);

        $terisiSaatIni = Pesanan::where('id_meja', $meja->id_meja)
            ->where('status', 'belum_bayar')
            ->sum('jumlah_orang');

        $sisa = $meja->kapasitas - $terisiSaatIni;

        if ((int) $request->jumlah_orang > $sisa) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', "Jumlah orang melebihi sisa kursi meja ini (sisa {$sisa} kursi).");
        }

        $pesanan = Pesanan::create([
            'tanggal'      => date('Y-m-d'),
            'id_meja'      => $request->id_meja,
            'jumlah_orang' => $request->jumlah_orang,
            'id_user'      => Auth::user()->id_user,
            'total_harga'  => 0,
            'status'       => 'belum_bayar',
        ]);

        $total = 0;

        foreach ($request->menu as $key => $id_menu) {
            $menu       = Menu::findOrFail($id_menu);
            $jumlah     = (int) ($request->jumlah[$key] ?? 1);
            $tipeHarga  = $request->tipe_harga[$key] ?? 'normal';
            $hargaPakai = (int) ($request->harga_pakai[$key] ?? $menu->harga);
            $subtotal   = $hargaPakai * $jumlah;
            $catatan    = $request->catatan[$key] ?? null;

            DetailPesanan::create([
                'id_pesanan'  => $pesanan->id_pesanan,
                'id_menu'     => $id_menu,
                'jumlah'      => $jumlah,
                'subtotal'    => $subtotal,
                'tipe_harga'  => $tipeHarga,
                'harga_pakai' => $hargaPakai,
                'catatan'     => $catatan,
                'is_new'      => 1,
                'jumlah_awal' => null,
            ]);

            $total += $subtotal;
        }

        $pesanan->update(['total_harga' => $total]);

        $this->refreshMejaStatus($request->id_meja);

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

    $meja = Meja::all()->map(function ($m) use ($pesanan) {
        $terisi = Pesanan::where('id_meja', $m->id_meja)
            ->where('status', 'belum_bayar')
            ->when($m->id_meja == $pesanan->id_meja, fn ($q) => $q->where('id_pesanan', '!=', $pesanan->id_pesanan))
            ->sum('jumlah_orang');

        $m->terisi = $terisi;
        $m->sisa   = max($m->kapasitas - $terisi, 0);

        return $m;
    })->filter(fn ($m) => $m->sisa > 0 || $m->id_meja == $pesanan->id_meja)->values();

    return view('kasir.pesanan.edit', compact('pesanan', 'menu', 'meja', 'kategori'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'id_meja'      => 'required',
        'jumlah_orang' => 'required|integer|min:1',
        'menu'         => 'required|array|min:1',
        'jumlah'       => 'required|array|min:1',
    ], [
        'id_meja.required'      => 'Meja wajib dipilih.',
        'jumlah_orang.required' => 'Jumlah orang wajib diisi.',
        'jumlah_orang.min'      => 'Jumlah orang minimal 1.',
        'menu.required'         => 'Pesanan tidak boleh kosong, minimal 1 menu harus dipilih.',
        'jumlah.required'       => 'Jumlah menu wajib diisi.',
    ]);

    DB::beginTransaction();

    try {
        $pesanan = Pesanan::findOrFail($id);
        $oldMeja = $pesanan->id_meja;

        // ── Validasi sisa kursi kalau meja diganti atau jumlah orang berubah ──
        $mejaTujuan = Meja::lockForUpdate()->findOrFail($request->id_meja);

        $terisiMejaTujuan = Pesanan::where('id_meja', $request->id_meja)
            ->where('status', 'belum_bayar')
            ->where('id_pesanan', '!=', $pesanan->id_pesanan)
            ->sum('jumlah_orang');

        $sisaMejaTujuan = $mejaTujuan->kapasitas - $terisiMejaTujuan;

        if ((int) $request->jumlah_orang > $sisaMejaTujuan) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', "Jumlah orang melebihi sisa kursi meja ini (sisa {$sisaMejaTujuan} kursi).");
        }

        $existingDetails = DetailPesanan::where('id_pesanan', $id)->get();
        $processedIds = [];
        $total = 0;

        foreach ($request->menu as $key => $id_menu) {
            $menu = Menu::findOrFail($id_menu);

            $jumlahBaru = (int) ($request->jumlah[$key] ?? 1);
            $tipeHarga = $request->tipe_harga[$key] ?? 'normal';
            $hargaPakai = (int) ($request->harga_pakai[$key] ?? $menu->harga);
            $subtotal = $hargaPakai * $jumlahBaru;
            $catatanBaru = $request->catatan[$key] ?? null;

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
                $catatanLama  = $detail->catatan;

                $menuBerubah = $menuLama !== (int) $id_menu;
                $jumlahBertambah = $jumlahBaru > $jumlahLama;
                $catatanBerubah  = $catatanLama !== $catatanBaru;

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
                    'catatan'     => $catatanBaru,
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
                    'catatan'     => $catatanBaru,
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
            'id_meja'      => $request->id_meja,
            'jumlah_orang' => $request->jumlah_orang,
            'total_harga'  => $total,
        ]);

        if ($oldMeja != $request->id_meja) {
            $this->refreshMejaStatus($oldMeja);
        }
        $this->refreshMejaStatus($request->id_meja);

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
        'bayar_cash'        => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status === 'sudah_bayar') {
            DB::rollBack();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan ini sudah dibayar.');
        }

        $subtotal  = (int) ($pesanan->total_harga ?? 0);
        $pajak     = (int) round($subtotal * 0.07);

        $isElektronik = in_array($request->metode_pembayaran, ['card', 'qris']);

        $biayaCard = $isElektronik
                        ? (int) round(($subtotal + $pajak) * 0.02)
                        : 0;

        $totalBayar = $subtotal + $pajak + $biayaCard;

        $bayarCash       = null;
        $bayarElektronik = null;

        if ($isElektronik) {
            // ── Cek apakah ini split payment (cash + qris/card) ──
            $bayarCash = (int) ($request->bayar_cash ?? 0);

            if ($bayarCash > $totalBayar) {
                DB::rollBack();
                return back()->with('error', 'Nominal cash tidak boleh melebihi total pembayaran.');
            }

            $bayarElektronik = $totalBayar - $bayarCash;
            $bayar           = $totalBayar;
            $kembalian       = 0;
        } else {
            // ── Cash murni ──
            if ((int) $request->bayar < $totalBayar) {
                DB::rollBack();

                return back()->with(
                    'error',
                    'Jumlah bayar kurang. Total yang harus dibayar adalah Rp ' . number_format($totalBayar, 0, ',', '.')
                );
            }

            $bayar     = (int) $request->bayar;
            $kembalian = $bayar - $totalBayar;
        }

        $pesanan->update([
            'status'            => 'sudah_bayar',
            'metode_pembayaran' => $request->metode_pembayaran,
            'pajak'             => $pajak,
            'biaya_card'        => $biayaCard,
            'total_bayar'       => $totalBayar,
            'bayar'             => $bayar,
            'bayar_cash'        => $bayarCash,
            'bayar_elektronik'  => $bayarElektronik,
            'kembalian'         => $kembalian,
        ]);

        $this->refreshMejaStatus($pesanan->id_meja);

        DB::commit();

        return redirect()->route('struk.cetak', $pesanan->id_pesanan)
            ->with('success', 'Pembayaran berhasil diproses.');

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
private function refreshMejaStatus($idMeja)
{
    $meja = Meja::lockForUpdate()->findOrFail($idMeja);

    $terisi = Pesanan::where('id_meja', $idMeja)
        ->where('status', 'belum_bayar')
        ->sum('jumlah_orang');

    $sisa = $meja->kapasitas - $terisi;

    $meja->update([
        'status' => $sisa <= 0 ? 'terisi' : 'kosong',
    ]);

    return $sisa;
}
public function destroy($id)
{
    DB::beginTransaction();

    try {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status === 'sudah_bayar') {
            DB::rollBack();
            return back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dihapus.');
        }

        $idMeja = $pesanan->id_meja;

        DetailPesanan::where('id_pesanan', $pesanan->id_pesanan)->delete();
        $pesanan->delete();

        $this->refreshMejaStatus($idMeja);

        DB::commit();

        return redirect()->route('pesanan.index')
            ->with('success', 'Pesanan berhasil dihapus.');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
    }
}
}