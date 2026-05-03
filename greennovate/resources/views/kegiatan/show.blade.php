@extends('layouts.auth')

@section('title', $kegiatan->title . ' - Greennovate')

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
        <span class="text-gray-600 font-medium truncate max-w-xs">{{ $kegiatan->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===== KOLOM KIRI / UTAMA ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Banner --}}
            <div class="relative rounded-2xl overflow-hidden h-64 bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 shadow-lg">
                @if($kegiatan->image)
                    <img src="{{ asset('storage/' . $kegiatan->image) }}"
                         alt="{{ $kegiatan->title }}"
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

                {{-- Badge Status di atas hero --}}
                <div class="absolute top-4 left-4">
                    @if($kegiatan->status === 'open' && $kegiatan->isRegistrationOpen())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-white/95 text-green-700 shadow-md">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                            Pendaftaran Dibuka
                        </span>
                    @elseif($kegiatan->status === 'completed')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-white/95 text-gray-600 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Kegiatan Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-white/95 text-red-600 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Pendaftaran Ditutup
                        </span>
                    @endif
                </div>

                {{-- Judul di hero --}}
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h1 class="text-2xl font-bold text-white drop-shadow-md">
                        {{ $kegiatan->title }}
                    </h1>
                </div>
            </div>

            {{-- ===== DESKRIPSI KEGIATAN ===== --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Deskripsi Kegiatan</h2>
                </div>
                <div class="text-gray-600 leading-relaxed whitespace-pre-line text-sm">
                    {{ $kegiatan->description }}
                </div>
            </div>

            {{-- ===== PROGRES KEGIATAN ===== --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Progres Pendaftaran</h2>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <p class="text-2xl font-bold text-green-700">{{ $kegiatan->registered_count }}</p>
                        <p class="text-xs text-gray-500 mt-1">Terdaftar</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-700">{{ $kegiatan->quota }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Kuota</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-xl">
                        <p class="text-2xl font-bold {{ $kegiatan->remaining_quota === 0 ? 'text-red-600' : 'text-orange-600' }}">
                            {{ $kegiatan->remaining_quota }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Sisa Slot</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div>
                    <div class="flex justify-between text-xs text-gray-500 mb-2">
                        <span>{{ $kegiatan->progressPercentage() }}% kuota terisi</span>
                        <span>{{ $kegiatan->registered_count }}/{{ $kegiatan->quota }} peserta</span>
                    </div>
                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out
                            {{ $kegiatan->progressPercentage() >= 90 ? 'bg-gradient-to-r from-red-400 to-red-500' :
                               ($kegiatan->progressPercentage() >= 60 ? 'bg-gradient-to-r from-yellow-400 to-orange-400' :
                               'bg-gradient-to-r from-green-400 to-emerald-500') }}"
                            style="width: {{ $kegiatan->progressPercentage() }}%">
                        </div>
                    </div>
                    @if($kegiatan->remaining_quota === 0)
                        <p class="text-xs text-red-500 mt-2 font-medium">⚠️ Kuota peserta sudah penuh</p>
                    @elseif($kegiatan->remaining_quota <= 5)
                        <p class="text-xs text-orange-500 mt-2 font-medium">🔥 Tersisa {{ $kegiatan->remaining_quota }} slot lagi!</p>
                    @endif
                </div>
            </div>

            {{-- ===== KETENTUAN ===== --}}
            @if($kegiatan->terms)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Ketentuan yang Berlaku</h2>
                </div>
                <div class="space-y-2">
                    @foreach(explode("\n", $kegiatan->terms) as $term)
                        @if(trim($term) !== '')
                            <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
                                <div class="w-5 h-5 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600">{{ trim($term) }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ===== KOLOM KANAN / SIDEBAR ===== --}}
        <div class="space-y-5">

            {{-- Info Kegiatan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Informasi Kegiatan</h3>

                <div class="space-y-4">
                    {{-- Lokasi --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Lokasi</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $kegiatan->location }}</p>
                        </div>
                    </div>

                    {{-- Tanggal Kegiatan --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Tanggal Kegiatan</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $kegiatan->start_date->translatedFormat('d M Y') }}</p>
                            @if(!$kegiatan->start_date->isSameDay($kegiatan->end_date))
                                <p class="text-xs text-gray-500">s/d {{ $kegiatan->end_date->translatedFormat('d M Y') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Pendaftaran --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Pendaftaran</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $kegiatan->registration_open_at->translatedFormat('d M Y') }}</p>
                            <p class="text-xs text-gray-500">s/d {{ $kegiatan->registration_close_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    {{-- Kuota --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Kuota Peserta</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $kegiatan->quota }} orang</p>
                            <p class="text-xs {{ $kegiatan->remaining_quota === 0 ? 'text-red-500' : 'text-green-600' }}">
                                {{ $kegiatan->remaining_quota === 0 ? 'Kuota penuh' : $kegiatan->remaining_quota . ' slot tersedia' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Daftar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                @php $disabledReason = $kegiatan->registration_disabled_reason; @endphp

                @if($disabledReason === null)
                    {{-- Bisa mendaftar --}}
                    {{-- 
                        Route pendaftaran: route('kegiatan.daftar.form', $kegiatan->slug)
                        akan diimplementasikan oleh tim fitur Pendaftaran Kegiatan.
                        Sementara menggunakan anchor '#' agar tidak error.
                    --}}
                    <button 
                        id="btn-daftar-kegiatan"
                        class="block w-full text-center bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                        
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Daftar Kegiatan
                        </span>
                    </button>
                    <p class="text-xs text-center text-gray-400 mt-3">
                        Dengan mendaftar, Anda menyetujui ketentuan yang berlaku.
                    </p>

                @else
                    {{-- Tidak bisa mendaftar — tombol disabled --}}
                    <button id="btn-daftar-kegiatan"
                            disabled
                            class="w-full text-center bg-gray-100 text-gray-400 font-semibold py-3.5 px-6 rounded-xl cursor-not-allowed select-none">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Daftar Kegiatan
                        </span>
                    </button>
                    <div class="mt-3 flex items-start gap-2 p-3 bg-red-50 rounded-lg">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-xs text-red-600">{{ $disabledReason }}</p>
                    </div>
                @endif
            </div>

            {{-- Kembali ke daftar --}}
            <a href="{{ route('kegiatan.index') }}"
               class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Lihat Semua Kegiatan
            </a>

        </div>
    </div>
</div>
<!-- MODAL KONFIRMASI -->
<div id="modal-daftar" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-gray-100 p-6 transform transition-all scale-95 opacity-0" id="modal-content">
        
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900">Konfirmasi Pendaftaran</h2>
                <p class="text-xs text-gray-500">Pastikan data kegiatan sudah sesuai</p>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="bg-gray-50 rounded-xl p-4 space-y-3 mb-5">

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Kegiatan</span>
                <span class="font-semibold text-gray-800 text-right">
                    {{ $kegiatan->title }}
                </span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Lokasi</span>
                <span class="font-semibold text-gray-800 text-right">
                    {{ $kegiatan->location }}
                </span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-semibold text-gray-800">
                    {{ $kegiatan->start_date->translatedFormat('d M Y') }}
                </span>
            </div>

        </div>

        {{-- Warning --}}
        <div class="flex items-start gap-2 text-xs text-gray-500 mb-5">
            <svg class="w-4 h-4 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M13 16h-1v-4h-1m1-4h.01"/>
            </svg>
            <p>
                Dengan mendaftar, Anda menyetujui ketentuan yang berlaku pada kegiatan ini.
            </p>
        </div>

        {{-- Action --}}
        <div class="flex gap-3">
            <button id="btn-batal"
                class="w-1/2 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                Batal
            </button>

            <button id="btn-yakin"
                class="w-1/2 py-2.5 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-semibold hover:from-green-700 hover:to-emerald-700 transition shadow-md">
                Yakin & Daftar
            </button>
        </div>

    </div>
</div>
<script>
    const btnDaftar = document.getElementById('btn-daftar-kegiatan');
    const modal = document.getElementById('modal-daftar');
    const modalContent = document.getElementById('modal-content');
    const btnBatal = document.getElementById('btn-batal');
    const btnYakin = document.getElementById('btn-yakin');

    // OPEN MODAL
    btnDaftar.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    });

    // CLOSE MODAL FUNCTION
    function closeModal() {
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    btnBatal.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // PREVENT DOUBLE CLICK
    let isSubmitting = false;

    btnYakin.addEventListener('click', () => {

        if (isSubmitting) return;
        isSubmitting = true;

        btnYakin.innerText = "Memproses...";
        btnYakin.disabled = true;

        setTimeout(() => {

            alert('Pendaftaran berhasil 🎉');

            closeModal();

            isSubmitting = false;
            btnYakin.innerText = "Yakin & Daftar";
            btnYakin.disabled = false;

        }, 1000);
    });
</script>
@endsection
