<?php

namespace Database\Seeders;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class DemoTransaksiSeeder extends Seeder
{
    /**
     * Seeder data dummy transaksi untuk kebutuhan demo/bimbingan.
     *
     * Catatan penting:
     * - Seeder ini MENAMBAHKAN data, tidak menghapus data lama.
     * - Jalankan cukup 1 kali agar data demo tidak dobel.
     * - Data dibuat untuk 12 bulan terakhir agar filter 6 bulan dan 12 bulan terlihat berisi.
     */
    public function run(): void
    {
        $kasir = User::where('role', 'kasir')->first() ?? User::first();
        $mejaList = Meja::orderBy('id_meja')->get();
        $menuList = Menu::orderBy('id_menu')->get();

        if (!$kasir) {
            throw new RuntimeException('Seeder gagal: belum ada user/kasir di tabel users. Buat akun kasir dulu.');
        }

        if ($mejaList->isEmpty()) {
            throw new RuntimeException('Seeder gagal: belum ada data meja. Isi data meja dulu.');
        }

        if ($menuList->isEmpty()) {
            throw new RuntimeException('Seeder gagal: belum ada data menu. Isi data menu dulu.');
        }

        DB::transaction(function () use ($kasir, $mejaList, $menuList) {
            $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
            $now = Carbon::now();

            for ($i = 0; $i < 12; $i++) {
                $month = $startMonth->copy()->addMonths($i);
                $isCurrentMonth = $month->isSameMonth($now);

                $maxDay = $isCurrentMonth
                    ? max(1, (int) $now->format('d'))
                    : (int) $month->copy()->endOfMonth()->format('d');

                // Jumlah transaksi per bulan sengaja dibuat bervariasi agar laporan lebih realistis.
                $jumlahTransaksi = random_int(8, 15);

                // Bulan berjalan dibuat sedikit lebih ramai untuk demo laporan bulan ini.
                if ($isCurrentMonth) {
                    $jumlahTransaksi = random_int(10, 18);
                }

                for ($t = 0; $t < $jumlahTransaksi; $t++) {
                    $tanggalTransaksi = $month->copy()
                        ->day(random_int(1, $maxDay))
                        ->hour(random_int(10, 22))
                        ->minute(random_int(0, 59))
                        ->second(0);

                    $meja = $mejaList->random();
                    $metode = $this->pilihMetodePembayaran();
                    $jumlahJenisMenu = min(random_int(1, 3), $menuList->count());
                    $menuDipilih = $this->ambilMenuBerbobot($menuList, $jumlahJenisMenu);

                    $detailItems = [];
                    $subtotalPesanan = 0;

                    foreach ($menuDipilih as $menu) {
                        $tipeHarga = random_int(1, 100) <= 20 ? 'guide' : 'normal';
                        $hargaNormal = (int) ($menu->harga ?? 0);
                        $hargaGuide = (int) ($menu->harga_guide ?? 0);
                        $hargaPakai = ($tipeHarga === 'guide' && $hargaGuide > 0) ? $hargaGuide : $hargaNormal;

                        // Supaya data terlihat bervariasi, menu tertentu dibuat lebih sering qty 2.
                        $jumlah = random_int(1, 100) <= 25 ? 2 : 1;
                        if (random_int(1, 100) <= 8) {
                            $jumlah = 3;
                        }

                        $subtotalItem = $hargaPakai * $jumlah;
                        $subtotalPesanan += $subtotalItem;

                        $detailItems[] = [
                            'id_menu' => $menu->id_menu,
                            'jumlah' => $jumlah,
                            'subtotal' => $subtotalItem,
                            'tipe_harga' => $tipeHarga,
                            'harga_pakai' => $hargaPakai,
                        ];
                    }

                    $service = (int) round($subtotalPesanan * 0.07);
                    $biayaCard = $metode === 'card'
                        ? (int) round(($subtotalPesanan + $service) * 0.02)
                        : 0;

                    $totalBayar = $subtotalPesanan + $service + $biayaCard;

                    if ($metode === 'cash') {
                        $bayar = (int) (ceil($totalBayar / 5000) * 5000);
                        if ($bayar < $totalBayar) {
                            $bayar += 5000;
                        }
                        $kembalian = $bayar - $totalBayar;
                    } else {
                        $bayar = $totalBayar;
                        $kembalian = 0;
                    }

                    $idPesanan = $this->insertPesanan([
                        'tanggal' => $tanggalTransaksi,
                        'id_meja' => $meja->id_meja,
                        'id_user' => $kasir->id_user,
                        'total_harga' => $subtotalPesanan,
                        'status' => 'sudah_bayar',
                        'metode_pembayaran' => $metode,
                        'pajak' => $service,
                        'biaya_card' => $biayaCard,
                        'total_bayar' => $totalBayar,
                        'bayar' => $bayar,
                        'kembalian' => $kembalian,
                        'created_at' => $tanggalTransaksi,
                        'updated_at' => $tanggalTransaksi,
                    ]);

                    foreach ($detailItems as $detail) {
                        $this->insertDetailPesanan([
                            'id_pesanan' => $idPesanan,
                            'id_menu' => $detail['id_menu'],
                            'jumlah' => $detail['jumlah'],
                            'jumlah_awal' => null,
                            'subtotal' => $detail['subtotal'],
                            'is_new' => 0,
                            'tipe_harga' => $detail['tipe_harga'],
                            'harga_pakai' => $detail['harga_pakai'],
                            'created_at' => $tanggalTransaksi,
                            'updated_at' => $tanggalTransaksi,
                        ]);
                    }
                }
            }

            // Semua transaksi demo dibuat sudah dibayar, jadi meja dikembalikan kosong.
            if (Schema::hasColumn('meja', 'status')) {
                Meja::query()->update(['status' => 'kosong']);
            }
        });
    }

    private function pilihMetodePembayaran(): string
    {
        $angka = random_int(1, 100);

        return match (true) {
            $angka <= 45 => 'cash',
            $angka <= 85 => 'qris',
            default => 'card',
        };
    }

    private function ambilMenuBerbobot($menuList, int $jumlah)
    {
        $pool = collect();

        foreach ($menuList as $menu) {
            $nama = strtolower($menu->nama_menu ?? '');
            $bobot = 10;

            if (str_contains($nama, 'ayam goreng')) {
                $bobot = 35;
            } elseif (str_contains($nama, 'matcha')) {
                $bobot = 28;
            } elseif (str_contains($nama, 'ayam bakar')) {
                $bobot = 22;
            } elseif (str_contains($nama, 'ayam golek')) {
                $bobot = 18;
            } elseif (str_contains($nama, 'ayam geprek')) {
                $bobot = 16;
            }

            for ($i = 0; $i < $bobot; $i++) {
                $pool->push($menu);
            }
        }

        $hasil = collect();
        $percobaan = 0;

        while ($hasil->count() < $jumlah && $percobaan < 100) {
            $pilihan = $pool->random();

            if (!$hasil->contains(fn ($item) => $item->id_menu === $pilihan->id_menu)) {
                $hasil->push($pilihan);
            }

            $percobaan++;
        }

        return $hasil;
    }

    private function insertPesanan(array $data): int
    {
        $kolomTersedia = Schema::getColumnListing('pesanan');
        $dataAman = collect($data)
            ->only($kolomTersedia)
            ->toArray();

        return (int) DB::table('pesanan')->insertGetId($dataAman, 'id_pesanan');
    }

    private function insertDetailPesanan(array $data): void
    {
        $kolomTersedia = Schema::getColumnListing('detail_pesanan');
        $dataAman = collect($data)
            ->only($kolomTersedia)
            ->toArray();

        DB::table('detail_pesanan')->insert($dataAman);
    }
}
