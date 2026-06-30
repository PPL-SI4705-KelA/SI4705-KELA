@extends('layouts.admin')

@section('title', 'Dashboard Admin – Greennovate')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data sistem Greennovate')

@section('content')

{{-- Flash Message --}}
@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
        {{ session('success') }}
    </div>
@endif

{{-- ===== SECTION 1: TUGAS VALIDASI & KEUANGAN (CRITICAL ADMIN ACTION) ===== --}}
<div class="mb-8">
    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Tugas Validasi & Keuangan</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        
        {{-- Total Dana Donasi --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-900">
                    Rp {{ number_format($stats['total_donasi_sukses'], 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-0.5">Total Dana Donasi</div>
            </div>
        </div>

        {{-- Total Dana Pembelian --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-900">
                    Rp {{ number_format($stats['total_pembelian_sukses'], 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-0.5">Total Penjualan Pohon</div>
            </div>
        </div>

        {{-- Donasi Pending Verifikasi --}}
        <a href="{{ route('admin.donasi.index', ['status' => 'pending']) }}" 
           class="group bg-white rounded-2xl p-5 border {{ $stats['donasi_pending_count'] > 0 ? 'border-amber-200 bg-amber-50/20' : 'border-gray-100' }} shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded-xl bg-amber-100/80 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                @if($stats['donasi_pending_count'] > 0)
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                @endif
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900 flex items-baseline gap-1.5">
                    {{ $stats['donasi_pending_count'] }}
                    @if($stats['donasi_pending_count'] > 0)
                        <span class="text-xs font-semibold text-amber-600">Butuh Verifikasi</span>
                    @endif
                </div>
                <div class="text-xs text-gray-400 group-hover:text-[#0D8B41] transition-colors mt-0.5">Donasi Pending &rarr;</div>
            </div>
        </a>

        {{-- Pembelian Pending Verifikasi --}}
        <a href="{{ route('admin.pembelian.index', ['status' => 'pending']) }}" 
           class="group bg-white rounded-2xl p-5 border {{ $stats['pembelian_pending_count'] > 0 ? 'border-orange-200 bg-orange-50/20' : 'border-gray-100' }} shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded-xl bg-orange-100/80 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($stats['pembelian_pending_count'] > 0)
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                    </span>
                @endif
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900 flex items-baseline gap-1.5">
                    {{ $stats['pembelian_pending_count'] }}
                    @if($stats['pembelian_pending_count'] > 0)
                        <span class="text-xs font-semibold text-orange-600">Butuh Verifikasi</span>
                    @endif
                </div>
                <div class="text-xs text-gray-400 group-hover:text-[#0D8B41] transition-colors mt-0.5">Pembelian Pending &rarr;</div>
            </div>
        </a>

        {{-- Pesan Belum Dibaca --}}
        <a href="{{ route('admin.chat.index') }}" 
           class="group bg-white rounded-2xl p-5 border {{ $stats['unread_chat_count'] > 0 ? 'border-red-200 bg-red-50/20' : 'border-gray-100' }} shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="w-9 h-9 rounded-xl bg-red-100/80 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                @if($stats['unread_chat_count'] > 0)
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                @endif
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900 flex items-baseline gap-1.5">
                    {{ $stats['unread_chat_count'] }}
                    @if($stats['unread_chat_count'] > 0)
                        <span class="text-xs font-semibold text-red-600">Baru</span>
                    @endif
                </div>
                <div class="text-xs text-gray-400 group-hover:text-[#0D8B41] transition-colors mt-0.5">Pesan Belum Dibaca &rarr;</div>
            </div>
        </a>

    </div>
</div>

{{-- ===== SECTION 2: DAMPAK LINGKUNGAN & KEGIATAN PENANAMAN ===== --}}
<div class="mb-8">
    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Dampak & Kegiatan Penanaman</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        {{-- Total Pohon Terbeli --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-lime-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">
                    {{ number_format($stats['total_pohon_terbeli'], 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">Pohon</span>
                </div>
                <div class="text-xs text-gray-400 mt-0.5">Pohon Dikontribusikan (Terbeli)</div>
            </div>
        </div>

        {{-- Total Pohon Terealisasi Ditanam --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">
                    {{ number_format($stats['total_pohon_ditanam'] ?? 0, 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">Pohon</span>
                </div>
                <div class="text-xs text-gray-400 mt-0.5">Pohon Terealisasi Ditanam</div>
            </div>
        </div>

        {{-- Total Kegiatan Aktif --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">
                    {{ $stats['total_kegiatan'] }} <span class="text-xs font-normal text-gray-400">Kegiatan</span>
                </div>
                <div class="text-xs text-gray-400 mt-0.5">Kegiatan Berlangsung (Aktif)</div>
            </div>
        </div>

    </div>
</div>

{{-- ===== SECTION 3: RINGKASAN DATA & SISTEM ===== --}}
<div class="mb-8">
    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Statistik Sistem</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

        {{-- Total Pengguna --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $stats['total_users'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">Total Pengguna (User)</div>
            </div>
        </div>

        {{-- Total Petugas --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $stats['total_petugas'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">Total Petugas</div>
            </div>
        </div>

        {{-- Total Lokasi Lahan --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $stats['total_lokasi'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">Total Lokasi Lahan</div>
            </div>
        </div>

        {{-- Total Jenis Pohon --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $stats['total_jenis_pohon'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">Total Jenis Pohon</div>
            </div>
        </div>

    </div>
</div>

{{-- ===== PENGGUNA TERBARU ===== --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-800 text-sm">Pengguna Terbaru</h2>
        <span class="text-xs text-gray-400">5 terbaru</span>
    </div>

    @if($users->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm">Belum ada pengguna terdaftar.</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($users as $user)
            <div class="flex items-center gap-4 px-6 py-3.5">
                <div class="w-9 h-9 rounded-full bg-[#0D8B41] flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email ?? $user->phone }}</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $user->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ===== QUICK ACCESS ===== --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="font-bold text-gray-800 text-sm mb-4">Akses Cepat</h2>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.kegiatan.index') }}"
           class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Kelola Kegiatan
        </a>
        <a href="{{ route('admin.lokasi.index') }}"
           class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Kelola Lokasi Lahan
        </a>
    </div>
</div>

@endsection