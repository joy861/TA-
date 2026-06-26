<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;

class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::with('kategori')->get();
        return view('admin.menu.index', compact('menu'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'   => 'required',
            'harga'       => 'required|numeric',
            'harga_guide' => 'required|numeric',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'status'      => 'required|in:tersedia,habis',
        ]);

        Menu::create([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'harga_guide' => $request->harga_guide,
            'id_kategori' => $request->id_kategori,
            'status'      => $request->status,
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $menu     = Menu::findOrFail($id);
        $kategori = Kategori::all();
        return view('admin.menu.edit', compact('menu', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'nama_menu'   => 'required',
            'harga'       => 'required|numeric',
            'harga_guide' => 'required|numeric',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'status'      => 'required|in:tersedia,habis',
        ]);

        $menu->update([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'harga_guide' => $request->harga_guide,
            'id_kategori' => $request->id_kategori,
            'status'      => $request->status,
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diupdate');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus');
    }

    public function cetak()
{
    $menu = Menu::with('kategori')->orderBy('id_kategori')->get();
    return view('admin.menu.cetak', compact('menu'));
}
}
