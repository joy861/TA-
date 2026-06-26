<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data lama
        DB::table('detail_pesanan')->truncate();
        DB::table('pesanan')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Menu IDs yang tersedia (dari database)
        $menuIds = [56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78];
        
        // User IDs yang tersedia (dari database)
        $userIds = [1, 4, 5]; // admin, kasir, kasir
        
        // Meja IDs yang tersedia (dari database)
        $mejaIds = [4, 5, 6, 7, 8, 9, 10, 11, 12];
        
        // Harga menu (dari database)
        $menuPrices = [
            56 => 18000, 57 => 22000, 58 => 28000, 59 => 30000, 60 => 32000,
            61 => 33000, 62 => 33000, 63 => 34000, 64 => 29000, 65 => 35000,
            66 => 25000, 67 => 30000, 68 => 22000, 69 => 20000, 70 => 18000,
            71 => 22000, 72 => 35000, 73 => 25000, 74 => 35000, 75 => 32000,
            76 => 30000, 77 => 38000, 78 => 45000
        ];
        
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::now();
        
        $pesananRecords = [];
        $detailRecords = [];
        $pesananId = 1;
        
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            // Random 2-4 orders per day
            $ordersPerDay = rand(2, 4);
            
            for ($i = 0; $i < $ordersPerDay; $i++) {
                // Randomize order details
                $userId = $userIds[array_rand($userIds)];
                $mejaId = $mejaIds[array_rand($mejaIds)];
                $numItems = rand(1, 4);
                
                // Calculate total and details
                $totalHarga = 0;
                $itemDetails = [];
                
                for ($j = 0; $j < $numItems; $j++) {
                    $menuId = $menuIds[array_rand($menuIds)];
                    $jumlah = rand(1, 3);
                    $harga = $menuPrices[$menuId];
                    $subtotal = $harga * $jumlah;
                    $totalHarga += $subtotal;
                    
                    $itemDetails[] = [
                        'id_pesanan' => $pesananId,
                        'id_menu' => $menuId,
                        'jumlah' => $jumlah,
                        'jumlah_awal' => $jumlah,
                        'subtotal' => $subtotal,
                        'tipe_harga' => 'normal',
                        'harga_pakai' => $harga,
                        'is_new' => 0,
                        'created_at' => $date->copy()->addHours(rand(7, 20))->addMinutes(rand(0, 59)),
                        'updated_at' => $date->copy()->addHours(rand(7, 20))->addMinutes(rand(0, 59)),
                    ];
                }
                
                // Calculate tax and payment
                $pajak = (int) ($totalHarga * 0.1); // 10% tax
                $totalBayar = $totalHarga + $pajak;
                
                // Random payment method
                $metodeList = ['tunai', 'kartu'];
                $metode = $metodeList[array_rand($metodeList)];
                
                if ($metode === 'kartu') {
                    $biayaCard = (int) ($totalBayar * 0.03); // 3% card fee
                    $totalBayar += $biayaCard;
                } else {
                    $biayaCard = 0;
                }
                
                // Random payment status and amount
                $statusList = ['belum_bayar', 'sudah_bayar'];
                $status = $statusList[array_rand($statusList)];
                
                if ($status === 'sudah_bayar') {
                    $bayar = $totalBayar;
                    $kembalian = 0;
                    
                    // Sometimes round up to nearest thousand
                    if (rand(0, 1)) {
                        $bayar = (int) ceil($totalBayar / 1000) * 1000;
                        $kembalian = $bayar - $totalBayar;
                    }
                } else {
                    $bayar = 0;
                    $kembalian = 0;
                }
                
                $pesananRecords[] = [
                    'id_pesanan' => $pesananId,
                    'tanggal' => $date->copy()->addHours(rand(7, 20))->addMinutes(rand(0, 59))->format('Y-m-d H:i:s'),
                    'id_meja' => $mejaId,
                    'id_user' => $userId,
                    'total_harga' => $totalHarga,
                    'status' => $status,
                    'metode_pembayaran' => $status === 'belum_bayar' ? null : $metode,
                    'pajak' => $pajak,
                    'biaya_card' => $biayaCard,
                    'total_bayar' => $totalBayar,
                    'bayar' => $bayar,
                    'kembalian' => $kembalian,
                    'created_at' => $date->copy(),
                    'updated_at' => $date->copy(),
                ];
                
                $detailRecords = array_merge($detailRecords, $itemDetails);
                $pesananId++;
            }
            
            // Insert in batches of 100 to avoid memory issues
            if (count($pesananRecords) >= 100) {
                DB::table('pesanan')->insert($pesananRecords);
                $pesananRecords = [];
            }
        }
        
        // Insert remaining pesanan
        if (count($pesananRecords) > 0) {
            DB::table('pesanan')->insert($pesananRecords);
        }
        
        // Insert detail pesanan in batches
        for ($i = 0; $i < count($detailRecords); $i += 100) {
            $batch = array_slice($detailRecords, $i, 100);
            DB::table('detail_pesanan')->insert($batch);
        }
    }
}
