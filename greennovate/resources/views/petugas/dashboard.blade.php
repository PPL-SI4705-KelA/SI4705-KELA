@extends('layouts.petugas')

@section('title', 'Kegiatan Saya - Petugas')
@section('header', 'Kegiatan Saya')

@section('content')
<div class="max-w-6xl">
    <!-- Section Title -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-1">Kegiatan Aktif</h2>
        <p class="text-sm text-gray-500">Kegiatan yang sedang berlangsung dan membutuhkan pencatatan</p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($kegiatans as $kegiatan)
            @php
                $percentage = $kegiatan->target_pohon > 0 ? min(round(($kegiatan->realisasi_pohon / $kegiatan->target_pohon) * 100), 100) : 0;
            @endphp
            <!-- Dynamic Card -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-gray-900 text-[15px] mb-1.5">{{ $kegiatan->nama }}</h3>
                        <div class="flex items-center gap-1.5 text-[13px] text-gray-500 mb-1">
                            <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $kegiatan->lokasiLahan ? $kegiatan->lokasiLahan->alamat : 'Lokasi tidak ditentukan' }}
                        </div>
                        <div class="flex items-center gap-1.5 text-[13px] text-gray-500">
                            <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    <div class="bg-[#eaf6ef] text-[#1a8245] text-[11px] font-bold px-2.5 py-1 rounded-full">
                        {{ $kegiatan->status }}
                    </div>
                </div>

                <div class="mb-5 mt-6">
                    <div class="flex justify-between items-end mb-2">
                        <div class="flex items-center gap-1.5 text-[13px] font-medium text-gray-500">
                            <svg class="w-4 h-4 text-[#1a8245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Pohon Ditanam
                        </div>
                        <div class="text-[14px] font-bold text-gray-900">
                            {{ number_format($kegiatan->realisasi_pohon, 0, ',', '.') }} <span class="text-xs font-medium text-gray-400">/ {{ number_format($kegiatan->target_pohon, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="w-full bg-[#f1f5f9] rounded-full h-2.5 mb-1.5 overflow-hidden">
                        <div class="bg-[#1a8245] h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="text-[11px] text-gray-400 font-medium">{{ $percentage }}% tercapai</div>
                </div>

                <div class="flex gap-3">
                    <button class="flex-1 bg-[#1a8245] hover:bg-green-800 text-white text-[13px] font-semibold py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Catat Realisasi
                    </button>
                    <button class="px-5 border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-[13px] font-semibold rounded-xl flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Dokumentasi
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white border border-gray-100 rounded-2xl shadow-sm">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-sm font-bold text-gray-500">Tidak ada kegiatan aktif</h3>
                <p class="text-xs text-gray-400 mt-1">Anda belum ditugaskan ke kegiatan apapun saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
