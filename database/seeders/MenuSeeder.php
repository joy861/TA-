<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menu')->insert([

            // =====================
            // MINUMAN (Kategori 3)
            // =====================

            [
                'nama_menu' => 'Espresso',
                'harga' => 18000,
                'harga_guide' => 22000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Americano',
                'harga' => 22000,
                'harga_guide' => 25000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Cappuccino',
                'harga' => 28000,
                'harga_guide' => 32000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Cafe Latte',
                'harga' => 30000,
                'harga_guide' => 35000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Mocha',
                'harga' => 32000,
                'harga_guide' => 37000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Caramel Latte',
                'harga' => 33000,
                'harga_guide' => 38000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Vanilla Latte',
                'harga' => 33000,
                'harga_guide' => 38000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Hazelnut Latte',
                'harga' => 34000,
                'harga_guide' => 39000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Macchiato',
                'harga' => 29000,
                'harga_guide' => 34000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Affogato',
                'harga' => 35000,
                'harga_guide' => 40000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Chocolate Milk',
                'harga' => 25000,
                'harga_guide' => 30000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Matcha Latte',
                'harga' => 30000,
                'harga_guide' => 35000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Thai Tea',
                'harga' => 22000,
                'harga_guide' => 27000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Green Tea',
                'harga' => 20000,
                'harga_guide' => 25000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Lemon Tea',
                'harga' => 18000,
                'harga_guide' => 22000,
                'id_kategori' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // MAKANAN (Kategori 1)
            // =====================

            [
                'nama_menu' => 'French Fries',
                'harga' => 22000,
                'harga_guide' => 27000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Chicken Wings',
                'harga' => 35000,
                'harga_guide' => 40000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Onion Rings',
                'harga' => 25000,
                'harga_guide' => 30000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Nasi Goreng Spesial',
                'harga' => 35000,
                'harga_guide' => 40000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Mie Goreng Jawa',
                'harga' => 32000,
                'harga_guide' => 37000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Ayam Geprek',
                'harga' => 30000,
                'harga_guide' => 35000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Chicken Katsu',
                'harga' => 38000,
                'harga_guide' => 43000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Burger Beef',
                'harga' => 45000,
                'harga_guide' => 50000,
                'id_kategori' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}