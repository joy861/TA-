<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MejaSeeder extends Seeder
{
    public function run(): void
    {
        $meja = [];
        
        for ($i = 1; $i <= 4; $i++) {
            $meja[] = [
                'nomor_meja' => $i,
                'kapasitas' => 4,
                'status' => 'kosong',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('meja')->insert($meja);
    }
}
