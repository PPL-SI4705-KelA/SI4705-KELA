@extends('layouts.auth')

@section('title', $kegiatan->nama . ' - Greennovate')

@section('content')
<div class="w-full max-w-5xl px-6 mt-4 pb-16">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-green-600 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('kegiatan.index') }}" class="hover:text-green-600 transition-colors">Kegiatan</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 font-medium truncate max-w-xs">{{ $kegiatan->nama }}</span>
    </nav>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===== KOLOM KIRI / UTAMA ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Banner --}}
            <div class="relative rounded-2xl overflow-hidden h-64 bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 shadow-lg">
                @if($kegiatan->image)
                    <img src="{{ asset('storage/' . $kegiatan->image) }}"
                         alt="{{ $kegiatan->nama }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                @else
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 200 200" fill="white">
                            <path d="M0,100 C50,50 150,150 200,100 L200,200 L0,200 Z"/>
                            <path d="M0,150 C50,100 150,180 200,140 L200,200 L0,200 Z" opacity="0.5"/>
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-white/80 p-6">
                        <svg class="w-16 h-16 mb-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif

                {{-- Badge Status --}}
                <div class="absolute top-4 left-4">
                    @php
                        $badgeClass = match($kegiatan->status) {
                            'Berlangsung' => 'bg-white/95 text-green-700',
                            'Persiapan'   => 'bg-white/95 text-yellow-700',
                            'Selesai'     => 'bg-white/95 text-gray-600',
                            'Dibatalkan'  => 'bg-white/95 text-red-600',
                            default       => 'bg-white/95 text-gray-600',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold shadow-md {{ $badgeClass }}">
                        @if($kegiatan->status === 'Berlangsung')
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                        @elseif($kegiatan->status === 'Selesai')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($kegiatan->status === 'Dibatalkan')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        @endif
                        {{ $kegiatan->status }}
                    </span>
                </div>

                {{-- Judul di hero --}}
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h1 class="text-2xl font-bold text-white drop-shadow-md">
                        {{ $kegiatan->nama }}
                    </h1>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Deskripsi Kegiatan
                </h2>
                <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi.' }}
                </div>
            </div>

            {{-- Progress Penanaman --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Progress Penanaman
                </h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-green-700 font-medium mb-1">Target Pohon</p>
                        <p class="text-2xl font-bold text-green-800">{{ number_format($kegiatan->target_pohon) }}</p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-emerald-700 font-medium mb-1">Realisasi Pohon</p>
                        <p class="text-2xl font-bold text-emerald-800">{{ number_format($kegiatan->realisasi_pohon) }}</p>
                    </div>
                </div>
                @if($kegiatan->target_pohon > 0)
                    @php $persen = min(100, round(($kegiatan->realisasi_pohon / $kegiatan->target_pohon) * 100)); @endphp
                    <div>
                        <div class="flex justify-between text-sm text-gray-500 mb-2">
                            <span>Progress Realisasi</span>
                            <span class="font-semibold text-green-700">{{ $persen }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-400 h-3 rounded-full transition-all duration-700"
                                 style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Syarat & Ketentuan --}}
            @if($kegiatan->terms)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Syarat & Ketentuan
                    </h2>
                    <div class="text-gray-600 leading-relaxed whitespace-pre-line text-sm">
                        {{ $kegiatan->terms }}
                    </div>
                </div>
            @endif

            {{-- Galeri Dokumentasi Lapangan --}}
            @if($kegiatan->dokumentasis && $kegiatan->dokumentasis->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Dokumentasi Lapangan
                        <span class="ml-auto text-xs font-normal text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                            {{ $kegiatan->dokumentasis->count() }} foto
                        </span>
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($kegiatan->dokumentasis as $dok)
                            <a href="{{ route('dokumentasi.foto', $dok->id) }}"
                               target="_blank"
                               class="group relative aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 hover:border-green-400 transition-all duration-200 hover:shadow-md">
                                <img src="{{ route('dokumentasi.foto', $dok->id) }}"
                                     alt="Foto dokumentasi kegiatan"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                {{-- Overlay on hover --}}
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                </div>
                                {{-- Upload date badge --}}
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent px-2 py-1.5">
                                    <p class="text-[10px] text-white/80">{{ $dok->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <p class="mt-3 text-xs text-gray-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Diunggah oleh Petugas Lapangan. Klik foto untuk melihat ukuran penuh.
                    </p>
                </div>
            @endif

        </div>

        {{-- ===== KOLOM KANAN / SIDEBAR ===== --}}
        <div class="space-y-6">

            {{-- Info Kegiatan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Informasi Kegiatan</h2>
                <ul class="space-y-4">

                    {{-- Tanggal --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Tanggal Kegiatan</p>
                            <p class="text-sm text-gray-800 font-semibold">
                                {{ $kegiatan->tanggal ? $kegiatan->tanggal->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                    </li>

                    {{-- Lokasi --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Lokasi Lahan</p>
                            <p class="text-sm text-gray-800 font-semibold">
                                {{ $kegiatan->lokasiLahan?->nama ?? 'Belum ditentukan' }}
                            </p>
                        </div>
                    </li>

                    {{-- Petugas --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Petugas</p>
                            <p class="text-sm text-gray-800 font-semibold">
                                {{ $kegiatan->petugas?->name ?? 'Belum ditentukan' }}
                            </p>
                        </div>
                    </li>

                    {{-- Status --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Status</p>
                            @php
                                $statusColor = match($kegiatan->status) {
                                    'Berlangsung' => 'bg-green-100 text-green-700',
                                    'Persiapan'   => 'bg-yellow-100 text-yellow-700',
                                    'Selesai'     => 'bg-gray-100 text-gray-600',
                                    'Dibatalkan'  => 'bg-red-100 text-red-600',
                                    default       => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                {{ $kegiatan->status }}
                            </span>
                        </div>
                    </li>

                    {{-- Pendaftaran --}}
                    @if($kegiatan->registration_open_at && $kegiatan->registration_close_at)
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Periode Pendaftaran</p>
                                <p class="text-sm text-gray-800 font-semibold">
                                    {{ $kegiatan->registration_open_at->translatedFormat('d M Y') }}
                                    –
                                    {{ $kegiatan->registration_close_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </li>
                    @endif

                    {{-- Quota --}}
                    @if($kegiatan->quota > 0)
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Kuota Peserta</p>
                                <p class="text-sm text-gray-800 font-semibold">
                                    {{ $kegiatan->registered_count }}/{{ $kegiatan->quota }} peserta
                                </p>
                                @if($kegiatan->quota > 0)
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1.5">
                                        <div class="bg-green-500 h-1.5 rounded-full"
                                             style="width: {{ $kegiatan->progressPercentage() }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endif

                </ul>
            </div>

            {{-- Tombol Daftar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                @if(auth()->check() && (auth()->user()->isPetugas() || auth()->user()->isAdmin()))
                    {{-- Petugas & Admin: tidak bisa mendaftar --}}
                    <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-700">
                                {{ auth()->user()->isPetugas() ? 'Anda adalah Petugas Lapangan' : 'Anda adalah Administrator' }}
                            </p>
                            <p class="text-xs text-blue-500 mt-0.5 leading-relaxed">
                                Pendaftaran kegiatan hanya untuk peserta umum.
                            </p>
                        </div>
                    </div>
                @elseif($kegiatan->isRegistrationOpen())
                    <a href="{{ route('kegiatan.daftar.form', $kegiatan->slug ?? $kegiatan->id) }}"
                       class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Daftar Sekarang
                    </a>
                @else
                    <button disabled
                            class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-400 font-semibold py-3 px-6 rounded-xl cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Pendaftaran Tidak Tersedia
                    </button>
                    @if($kegiatan->registration_disabled_reason)
                        <p class="text-xs text-gray-400 text-center mt-3 leading-relaxed">
                            {{ $kegiatan->registration_disabled_reason }}
                        </p>
                    @endif
                @endif
            </div>


            {{-- Kembali --}}
            <a href="{{ route('kegiatan.index') }}"
               class="w-full flex items-center justify-center gap-2 border border-gray-200 text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium py-3 px-6 rounded-xl transition-colors duration-200 bg-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Kegiatan
            </a>

        </div>
    </div>
</div>
@endsection