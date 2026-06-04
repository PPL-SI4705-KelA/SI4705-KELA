<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPohon;
use App\Models\KategoriPohon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JenisPohonController extends Controller
{
    /**
     * Tampilkan daftar jenis pohon dengan filter, search, dan pagination.
     *
     * GET /admin/jenis-pohon
     */
    public function index(Request $request)
    {
        $query = JenisPohon::with('kategori');

        // Search berdasarkan nama pohon
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_pohon_id', $request->kategori);
        }

        // Filter berdasarkan status (default: hanya aktif)
        if ($request->filled('status')) {
            if ($request->status === 'all') {
                // Tampilkan semua termasuk inactive
            } elseif ($request->status === 'inactive') {
                $query->where('status', 'inactive');
            } else {
                $query->where('status', 'active');
            }
        } else {
            $query->where('status', 'active');
        }

        // Tampilkan yang soft-deleted jika diminta
        if ($request->boolean('show_deleted')) {
            $query->withTrashed();
        }

        $jenisPohons = $query->latest()->paginate(20)->withQueryString();
        $kategoris   = KategoriPohon::orderBy('nama')->get();

        return view('admin.jenis-pohon.index', compact('jenisPohons', 'kategoris'));
    }

    /**
     * Form tambah jenis pohon baru.
     *
     * GET /admin/jenis-pohon/create
     */
    public function create()
    {
        $kategoris = KategoriPohon::orderBy('nama')->get();

        return view('admin.jenis-pohon.create', compact('kategoris'));
    }

    /**
     * Simpan jenis pohon baru ke database.
     *
     * POST /admin/jenis-pohon
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'              => ['required', 'string', 'min:3', 'max:100', 'unique:jenis_pohons,nama'],
            'nama_latin'        => ['nullable', 'string', 'max:100'],
            'kategori_pohon_id' => ['required', 'integer', 'exists:kategori_pohons,id'],
            'harga'             => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'status'            => ['required', 'in:active,inactive'],
        ], [
            'nama.required'              => 'Nama pohon wajib diisi.',
            'nama.min'                   => 'Nama pohon minimal 3 karakter.',
            'nama.max'                   => 'Nama pohon maksimal 100 karakter.',
            'nama.unique'                => "Jenis pohon '{$request->nama}' sudah terdaftar.",
            'kategori_pohon_id.required' => 'Kategori wajib dipilih.',
            'kategori_pohon_id.exists'   => 'Kategori tidak valid.',
            'harga.required'             => 'Harga wajib diisi.',
            'harga.numeric'              => 'Harga harus berupa angka.',
            'harga.min'                  => 'Harga minimal Rp 1.000.',
            'harga.max'                  => 'Harga maksimal Rp 10.000.000.',
            'status.required'            => 'Status wajib dipilih.',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['harga']      = round((float) $validated['harga']);

        JenisPohon::create($validated);

        return redirect()
            ->route('admin.jenis-pohon.index')
            ->with('success', 'Jenis pohon berhasil ditambahkan.');
    }

    /**
     * Form edit jenis pohon.
     *
     * GET /admin/jenis-pohon/{jenisPohon}/edit
     */
    public function edit(JenisPohon $jenisPohon)
    {
        $kategoris = KategoriPohon::orderBy('nama')->get();

        return view('admin.jenis-pohon.edit', compact('jenisPohon', 'kategoris'));
    }

    /**
     * Update jenis pohon di database.
     * Menggunakan optimistic locking via kolom `version`.
     *
     * PUT /admin/jenis-pohon/{jenisPohon}
     */
    public function update(Request $request, JenisPohon $jenisPohon)
    {
        $validated = $request->validate([
            'nama'              => ['required', 'string', 'min:3', 'max:100', Rule::unique('jenis_pohons', 'nama')->ignore($jenisPohon->id)],
            'nama_latin'        => ['nullable', 'string', 'max:100'],
            'kategori_pohon_id' => ['required', 'integer', 'exists:kategori_pohons,id'],
            'harga'             => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'status'            => ['required', 'in:active,inactive'],
            'version'           => ['required', 'integer'],
        ], [
            'nama.required'              => 'Nama pohon wajib diisi.',
            'nama.min'                   => 'Nama pohon minimal 3 karakter.',
            'nama.max'                   => 'Nama pohon maksimal 100 karakter.',
            'nama.unique'                => "Jenis pohon '{$request->nama}' sudah terdaftar.",
            'kategori_pohon_id.required' => 'Kategori wajib dipilih.',
            'kategori_pohon_id.exists'   => 'Kategori tidak valid.',
            'harga.required'             => 'Harga wajib diisi.',
            'harga.numeric'              => 'Harga harus berupa angka.',
            'harga.min'                  => 'Harga minimal Rp 1.000.',
            'harga.max'                  => 'Harga maksimal Rp 10.000.000.',
            'status.required'            => 'Status wajib dipilih.',
        ]);

        // Optimistic locking: cek version
        if ((int) $validated['version'] !== $jenisPohon->version) {
            return back()
                ->withInput()
                ->with('error', 'Data telah diubah oleh pengguna lain. Silakan muat ulang dan coba lagi.');
        }

        $validated['harga']   = round((float) $validated['harga']);
        $validated['version'] = $jenisPohon->version + 1;

        unset($validated['version_check']);

        $jenisPohon->update($validated);

        return redirect()
            ->route('admin.jenis-pohon.index')
            ->with('success', 'Jenis pohon berhasil diperbarui.');
    }

    /**
     * Soft delete / nonaktifkan jenis pohon.
     *
     * DELETE /admin/jenis-pohon/{jenisPohon}
     */
    public function destroy(JenisPohon $jenisPohon)
    {
        // Soft delete: set status = inactive, lalu soft-delete record
        $jenisPohon->update(['status' => 'inactive']);
        $jenisPohon->delete();

        return redirect()
            ->route('admin.jenis-pohon.index')
            ->with('success', 'Jenis pohon berhasil dihapus.');
    }
}
