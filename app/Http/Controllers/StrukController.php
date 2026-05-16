<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class StrukController extends Controller
{
    public function show($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
                    ->findOrFail($id);

        return view('kasir.struk.show', compact('pesanan'));
    }

    public function cetak($id)
    {
        $pesanan = Pesanan::with('detailPesanan.menu', 'meja', 'user')
                    ->findOrFail($id);

        return view('kasir.struk.cetak', compact('pesanan'));
    }
}