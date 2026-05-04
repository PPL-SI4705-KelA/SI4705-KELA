@extends('layouts.admin')

@section('title', 'Tambah Lokasi Lahan – Admin Greennovate')
@section('page-title', 'Tambah Lokasi Lahan')
@section('page-subtitle', 'Isi form berikut untuk menambahkan lokasi lahan baru')

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Form Lokasi Baru</h2>
            <a href="{{ route('admin.lokasi.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.lokasi.store') }}" class="px-8 py-6 space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nama Lokasi <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                       placeholder="cth: Hutan Kota Jakarta Selatan"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0D8B41]/40 focus:border-[#0D8B41] transition
                              {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('nama')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea id="alamat" name="alamat" rows="3"
                          placeholder="cth: Jl. Raya Pasar Minggu No. 1, Jakarta Selatan, DKI Jakarta"
                          class="w-full px-4 py-2.5 border rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0D8B41]/40 focus:border-[#0D8B41] transition resize-none
                                 {{ $errors->has('alamat') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Deskripsi
                    <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          placeholder="Jelaskan kondisi lahan, jenis tanaman yang cocok, akses transportasi, dll."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0D8B41]/40 focus:border-[#0D8B41] transition resize-none">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" id="btn-simpan"
                        class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold px-6 py-2.5 rounded-xl transition-all hover:shadow-lg text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Lokasi
                </button>
                <a href="{{ route('admin.lokasi.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-800 font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
