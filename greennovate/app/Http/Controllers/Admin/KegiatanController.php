<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Kegiatan::class);

        $query = Kegiatan::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        $kegiatans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        Gate::authorize('create', Kegiatan::class);

        return view('admin.kegiatan.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Kegiatan::class);

        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'lokasi'    => ['required', 'string', 'max:255'],
            'tanggal'   => ['required', 'date', 'after_or_equal:today'],
            'deskripsi' => ['nullable', 'string'],
            'target'    => ['required', 'integer', 'min:0'],
            'kuota'     => ['required', 'integer', 'min:0'],
            'status'    => ['required', 'in:aktif,nonaktif,selesai'],
        ], [
            'nama.required'          => 'Nama kegiatan wajib diisi.',
            'lokasi.required'        => 'Lokasi wajib diisi.',
            'tanggal.required'       => 'Tanggal wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'target.min'             => 'Target tidak boleh negatif.',
            'kuota.min'              => 'Kuota tidak boleh negatif.',
            'status.in'              => 'Status tidak valid.',
        ]);

        Kegiatan::create($validated);

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        Gate::authorize('view', $kegiatan);

        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        Gate::authorize('update', $kegiatan);

        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        Gate::authorize('update', $kegiatan);

        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'lokasi'    => ['required', 'string', 'max:255'],
            'tanggal'   => ['required', 'date'],
            'deskripsi' => ['nullable', 'string'],
            'target'    => ['required', 'integer', 'min:0'],
            'kuota'     => ['required', 'integer', 'min:0'],
            'status'    => ['required', 'in:aktif,nonaktif,selesai'],
        ], [
            'nama.required'   => 'Nama kegiatan wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'tanggal.required'=> 'Tanggal wajib diisi.',
            'target.min'      => 'Target tidak boleh negatif.',
            'kuota.min'       => 'Kuota tidak boleh negatif.',
            'status.in'       => 'Status tidak valid.',
        ]);

        $kegiatan->update($validated);

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        if (Gate::denies('delete', $kegiatan)) {
            return redirect()
                ->route('admin.kegiatan.index')
                ->with('error', 'Kegiatan tidak dapat dihapus karena sudah memiliki pendaftar. Ubah status menjadi "Nonaktif".');
        }

        $kegiatan->delete();

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
