<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;

class MejaController extends Controller
{
    public function index()
    {
        $meja = Meja::all();
        return view('admin.meja.index', compact('meja'));
    }

    public function create()
    {
        return view('admin.meja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|numeric|unique:meja,nomor_meja',
            'kapasitas'  => 'required|numeric',
            'status'     => 'required|in:kosong,terisi',
        ]);

        Meja::create([
            'nomor_meja' => $request->nomor_meja,
            'kapasitas'  => $request->kapasitas,
            'status'     => $request->status,
        ]);

        return redirect()->route('meja.index')->with('success', 'Meja berhasil ditambahkan');
    }

    public function edit($id)
    {
        $meja = Meja::findOrFail($id);
        return view('admin.meja.edit', compact('meja'));
    }

    public function update(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);

        $request->validate([
            'nomor_meja' => 'required|numeric|unique:meja,nomor_meja,' . $id . ',id_meja',
            'kapasitas'  => 'required|numeric',
            'status'     => 'required|in:kosong,terisi',
        ]);

        $meja->update([
            'nomor_meja' => $request->nomor_meja,
            'kapasitas'  => $request->kapasitas,
            'status'     => $request->status,
        ]);

        return redirect()->route('meja.index')->with('success', 'Meja berhasil diupdate');
    }

    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);
        $meja->delete();
        return redirect()->route('meja.index')->with('success', 'Meja berhasil dihapus');
    }

    public function updateStatus($id, $status)
    {
        $meja = Meja::findOrFail($id);
        $meja->update(['status' => $status]);
        return response()->json(['message' => 'Status meja berhasil diupdate']);
    }

    public function indexKasir()
    {
        $meja = Meja::all();
        return view('kasir.meja.index', compact('meja'));
    }

public function storeKasir(Request $request)
{
    $request->validate([
        'nomor_meja' => 'required|numeric|unique:meja,nomor_meja',
        'kapasitas'  => 'required|numeric|min:1',
    ], [
        'nomor_meja.required' => 'Nomor meja wajib diisi.',
        'nomor_meja.unique'   => 'Nomor meja sudah digunakan.',
        'kapasitas.required'  => 'Kapasitas wajib diisi.',
        'kapasitas.min'       => 'Kapasitas minimal 1.',
    ]);

    Meja::create([
        'nomor_meja' => $request->nomor_meja,
        'kapasitas'  => $request->kapasitas,
        'status'     => 'kosong',
    ]);

    return redirect()->route('kasir.meja.index')->with('success', 'Meja berhasil ditambahkan.');
}

public function updateKasir(Request $request, $id)
{
    $meja = Meja::findOrFail($id);

    $request->validate([
        'nomor_meja' => 'required|numeric|unique:meja,nomor_meja,' . $id . ',id_meja',
        'kapasitas'  => 'required|numeric|min:1',
    ], [
        'nomor_meja.required' => 'Nomor meja wajib diisi.',
        'nomor_meja.unique'   => 'Nomor meja sudah digunakan.',
        'kapasitas.required'  => 'Kapasitas wajib diisi.',
        'kapasitas.min'       => 'Kapasitas minimal 1.',
    ]);

    $meja->update([
        'nomor_meja' => $request->nomor_meja,
        'kapasitas'  => $request->kapasitas,
    ]);

    return redirect()->route('kasir.meja.index')->with('success', 'Meja berhasil diupdate.');
}

public function destroyKasir($id)
{
    $meja = Meja::findOrFail($id);

    if ($meja->status === 'terisi') {
        return back()->with('error', 'Meja yang sedang terisi tidak dapat dihapus.');
    }

    $meja->delete();

    return redirect()->route('kasir.meja.index')->with('success', 'Meja berhasil dihapus.');
}
}