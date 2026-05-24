<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

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
            'harga_guide' => 'required|numeric',  // tambah ini
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'status'      => 'required|in:tersedia,habis',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('menu', 'public');
        }

        Menu::create([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'harga_guide' => $request->harga_guide,  // tambah ini
            'id_kategori' => $request->id_kategori,
            'status'      => $request->status,
            'foto'        => $fotoPath,
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
            'harga_guide' => 'required|numeric',  // tambah ini
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'status'      => 'required|in:tersedia,habis',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'harga_guide' => $request->harga_guide,  // tambah ini
            'id_kategori' => $request->id_kategori,
            'status'      => $request->status,
        ];

        if ($request->hasFile('foto')) {
            if ($menu->foto) Storage::disk('public')->delete($menu->foto);
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        $menu->update($data);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diupdate');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->foto) Storage::disk('public')->delete($menu->foto);
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus');
    }
}