@extends('layouts.admin')

@section('title', 'Edit Jenis Pohon - Greennovate Admin')
@section('page-title', 'Edit Jenis Pohon')
@section('page-subtitle', 'Ubah informasi jenis pohon: ' . $jenisPohon->nama)

@section('content')
<div class="w-full max-w-2xl">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.jenis-pohon.index') }}"
           class="text-sm text-gray-400 hover:text-gray-600 mb-2 inline-block">
            ← Kembali ke Daftar Jenis Pohon
        </a>
        <h2 class="text-xl font-bold text-gray-900">Edit Jenis Pohon</h2>
        <p class="text-sm text-gray-500 mt-1">
            Ubah informasi jenis pohon: <span class="font-semibold text-gray-700">{{ $jenisPohon->nama }}</span>
        </p>
    </div>

    {{-- Info version --}}
    <div class="mb-4 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-xs">
        🔒 Versi data saat ini: <strong>v{{ $jenisPohon->version }}</strong>. Jika ada perubahan oleh admin lain saat Anda mengedit, sistem akan memberitahu saat menyimpan.
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form method="POST"
              action="{{ route('admin.jenis-pohon.update', $jenisPohon) }}"
              id="form-edit-pohon"
              class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            @include('admin.jenis-pohon._form')

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.jenis-pohon.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        id="btn-simpan-perubahan"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
