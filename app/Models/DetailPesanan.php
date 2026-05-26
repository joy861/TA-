<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';
    

protected $fillable = [
    'id_pesanan',
    'id_menu',
    'jumlah',
    'jumlah_awal', // ✅ tambahkan ini
    'subtotal',
    'is_new',
        'tipe_harga',   // ← tambah ini
    'harga_pakai'  // ← tambah ini
];

protected $attributes = [
    'is_new' => 0,
];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}