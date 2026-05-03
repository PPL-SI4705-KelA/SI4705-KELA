@extends('layouts.auth')

@section('title', 'Daftar Kegiatan - ' . $kegiatan->nama)

@section('content')
<div class="w-full max-w-2xl px-6 mt-4 pb-16">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="{{ route('dashboard') }}" class="hover:text-green-600 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('kegiatan.index') }}" class="hover:text-green-600 transition-colors">Kegiatan</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('kegiatan.show', $kegiatan->slug ?? $kegiatan->id) }}" class="hover:text-green-600 transition-colors truncate max-w-xs">
            {{ $kegiatan->nama }}
        </a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 font-medium">Pendaftaran</span>
    </nav>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Pendaftaran</h1>
        <p class="text-gray-500">Isi data diri Anda untuk mendaftar kegiatan <span class="font-semibold text-gray-700">{{ $kegiatan->nama }}</span></p>
    </div>

    {{-- Info Kegiatan Singkat --}}
    <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-8 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-green-900 text-sm">{{ $kegiatan->nama }}</p>
            <p class="text-green-700 text-xs">
                {{ $kegiatan->tanggal ? $kegiatan->tanggal->translatedFormat('d F Y') : '-' }}
                @if($kegiatan->quota > 0)
                    · Sisa kuota: {{ $kegiatan->remaining_quota }} peserta
                @endif
            </p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 text-sm">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('kegiatan.daftar', $kegiatan->slug ?? $kegiatan->id) }}">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="mb-5">
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nama_lengkap"
                       name="nama_lengkap"
                       value="{{ old('nama_lengkap', $user->name ?? '') }}"
                       placeholder="Masukkan nama lengkap Anda"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('nama_lengkap') border-red-400 @enderror">
                @error('nama_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No HP --}}
            <div class="mb-5">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nomor HP <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="no_hp"
                       name="no_hp"
                       value="{{ old('no_hp', $user->phone ?? '') }}"
                       placeholder="Contoh: 08123456789"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('no_hp') border-red-400 @enderror">
                @error('no_hp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="mb-6">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea id="alamat"
                          name="alamat"
                          rows="3"
                          placeholder="Masukkan alamat lengkap Anda"
                          class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm resize-none @error('alamat') border-red-400 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pernyataan --}}
            <div class="mb-8">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox"
                           name="pernyataan"
                           value="1"
                           class="mt-0.5 w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500 @error('pernyataan') border-red-400 @enderror">
                    <span class="text-sm text-gray-600 leading-relaxed">
                        Saya menyatakan bahwa data yang saya isi adalah benar, dan saya bersedia mengikuti kegiatan ini sesuai dengan ketentuan yang berlaku.
                    </span>
                </label>
                @error('pernyataan')
                    <p class="text-red-500 text-xs mt-1 ml-7">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 text-sm">
                    Kirim Pendaftaran
                </button>
                <a href="{{ route('kegiatan.show', $kegiatan->slug ?? $kegiatan->id) }}"
                   class="flex-1 text-center border border-gray-200 text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium py-3 px-6 rounded-xl transition-colors duration-200 text-sm">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>
@endsection