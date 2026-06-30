@extends('layouts.admin')

@section('title', 'Edit Kegiatan - Greennovate Admin')

@section('content')
<div class="w-full max-w-2xl px-6 mt-8 mb-16">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.kegiatan.index') }}"
           class="text-sm text-gray-400 hover:text-gray-600 mb-2 inline-block">
            ← Kembali ke Kelola Kegiatan
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Kegiatan</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah informasi kegiatan: <span class="font-semibold text-gray-700">{{ $kegiatan->nama }}</span></p>
    </div>

    {{-- Peringatan jika ada pendaftar --}}
    @if($kegiatan->hasPendaftar())
        <div class="mb-4 p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm">
            ⚠️ Kegiatan ini sudah memiliki pendaftar. Hapus tidak diizinkan; ubah status ke <strong>Nonaktif</strong> jika ingin menutupnya.
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form method="POST"
              action="{{ route('admin.kegiatan.update', $kegiatan) }}"
              class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            @include('admin.kegiatan._form')

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.kegiatan.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
