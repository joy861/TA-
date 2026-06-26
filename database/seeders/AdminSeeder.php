<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah user admin sudah ada
        if (DB::table('users')->where('username', 'admin')->exists()) {
            return;
        }

        DB::table('users')->insert([
            'nama' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'), // 🔥 wajib hash
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}