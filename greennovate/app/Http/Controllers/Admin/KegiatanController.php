<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Kegiatan::class);

        $query = Kegiatan::with('petugas');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $kegiatans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        Gate::authorize('create', Kegiatan::class);

        $petugasList  = User::where('role', 'petugas')->get();
        $lokasLahans  = DB::table('lokasi_lahans')->select('id', 'nama')->get();

        return view('admin.kegiatan.create', compact('petugasList', 'lokasLahans'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Kegiatan::class);

        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'lokasi_lahan_id' => ['required', 'integer', 'exists:lokasi_lahans,id'],
            'petugas_id'      => ['required', 'integer', 'exists:users,id'],
            'tanggal'         => ['required', 'date'],
            'target_pohon'    => ['required', 'integer', 'min:0'],
            'realisasi_pohon' => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'in:Persiapan,Berlangsung,Selesai,Dibatalkan'],
            'deskripsi'       => ['nullable', 'string'],
        ], [
            'nama.required'            => 'Nama kegiatan wajib diisi.',
            'lokasi_lahan_id.required' => 'Lokasi lahan wajib dipilih.',
            'lokasi_lahan_id.exists'   => 'Lokasi lahan tidak valid.',
            'petugas_id.required'      => 'Petugas wajib dipilih.',
            'petugas_id.exists'        => 'Petugas tidak valid.',
            'tanggal.required'         => 'Tanggal wajib diisi.',
            'target_pohon.min'         => 'Target pohon tidak boleh negatif.',
            'realisasi_pohon.min'      => 'Realisasi pohon tidak boleh negatif.',
        ]);

        $validated['realisasi_pohon'] = $validated['realisasi_pohon'] ?? 0;

        Kegiatan::create($validated);

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        Gate::authorize('view', $kegiatan);
        $kegiatan->load('petugas', 'lokasLahan');
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        Gate::authorize('update', $kegiatan);

        $petugasList = User::where('role', 'petugas')->get();
        $lokasLahans = DB::table('lokasi_lahans')->select('id', 'nama')->get();

        return view('admin.kegiatan.edit', compact('kegiatan', 'petugasList', 'lokasLahans'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        Gate::authorize('update', $kegiatan);

        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'lokasi_lahan_id' => ['required', 'integer', 'exists:lokasi_lahans,id'],
            'petugas_id'      => ['required', 'integer', 'exists:users,id'],
            'tanggal'         => ['required', 'date'],
            'target_pohon'    => ['required', 'integer', 'min:0'],
            'realisasi_pohon' => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'in:Persiapan,Berlangsung,Selesai,Dibatalkan'],
            'deskripsi'       => ['nullable', 'string'],
        ], [
            'nama.required'            => 'Nama kegiatan wajib diisi.',
            'lokasi_lahan_id.required' => 'Lokasi lahan wajib dipilih.',
            'petugas_id.required'      => 'Petugas wajib dipilih.',
            'tanggal.required'         => 'Tanggal wajib diisi.',
            'target_pohon.min'         => 'Target pohon tidak boleh negatif.',
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
                ->with('error', 'Kegiatan tidak dapat dihapus karena sudah Berlangsung/Selesai. Ubah status ke "Dibatalkan".');
        }

        $kegiatan->delete();

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
