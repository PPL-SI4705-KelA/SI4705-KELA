<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiLahan;
use Illuminate\Http\Request;

class LokasiLahanController extends Controller
{
    public function index()
    {
        $lokasis = LokasiLahan::latest()->paginate(10);
        return view('admin.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'alamat'    => 'required|string',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required'   => 'Nama lokasi wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        LokasiLahan::create($validated);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi lahan berhasil ditambahkan.');
    }

    public function edit(LokasiLahan $lokasi)
    {
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, LokasiLahan $lokasi)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'alamat'    => 'required|string',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required'   => 'Nama lokasi wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        $lokasi->update($validated);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi lahan berhasil diperbarui.');
    }

    public function destroy(LokasiLahan $lokasi)
    {
        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi lahan berhasil dihapus.');
    }
}
