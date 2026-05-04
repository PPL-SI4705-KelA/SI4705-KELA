@extends('layouts.landing')

@section('title', 'Daftar Kegiatan - Greennovate')

@section('content')
<div class="w-full max-w-6xl mx-auto px-6 mt-4 pb-16">

    {{-- Header --}}
    <div class="mb-10 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold tracking-widest uppercase bg-green-100 text-green-700 mb-4">
            🌿 Program Lingkungan
        </span>
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Daftar Kegiatan</h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto">
            Temukan kegiatan penghijauan dan lingkungan yang sesuai dengan minat Anda.
            Bergabunglah dan berkontribusi untuk bumi yang lebih hijau.
        </p>
    </div>

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

    {{-- ── FILTER PANEL ────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('kegiatan.index') }}"
          class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Filter Lokasi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Lokasi</label>
                <select name="lokasi"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasiList as $lokasi)
                        <option value="{{ $lokasi->id }}"
                                {{ request('lokasi') == $lokasi->id ? 'selected' : '' }}>
                            {{ $lokasi->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Status</label>
                <select name="status"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Status</option>
                    @foreach(['Persiapan', 'Berlangsung', 'Selesai', 'Dibatalkan'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Bulan --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Bulan</label>
                <select name="bulan"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i + 1 }}" {{ request('bulan') == $i + 1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Filter --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4
                               rounded-lg text-sm transition-colors duration-200">
                    Cari
                </button>
                @if(request()->hasAny(['lokasi', 'status', 'bulan', 'tahun']))
                    <a href="{{ route('kegiatan.index') }}"
                       class="flex-1 text-center border border-gray-200 text-gray-500 hover:text-gray-700
                              font-medium py-2.5 px-4 rounded-lg text-sm transition-colors duration-200">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Jumlah hasil --}}
    @if($kegiatan->total() > 0)
        <p class="text-sm text-gray-400 mb-5">
            Menampilkan <span class="font-semibold text-gray-600">{{ $kegiatan->firstItem() }}–{{ $kegiatan->lastItem() }}</span>
            dari <span class="font-semibold text-gray-600">{{ $kegiatan->total() }}</span> kegiatan
        </p>
    @endif

    {{-- ── EMPTY STATE ─────────────────────────────────────────────────────── --}}
    @if($kegiatan->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Belum ada kegiatan</h2>
            <p class="text-gray-400 text-sm mb-4">
                @if(request()->hasAny(['lokasi', 'status', 'bulan']))
                    Tidak ada kegiatan yang sesuai dengan filter yang dipilih.
                @else
                    Kegiatan akan segera hadir. Pantau terus halaman ini.
                @endif
            </p>
            @if(request()->hasAny(['lokasi', 'status', 'bulan']))
                <a href="{{ route('kegiatan.index') }}"
                   class="text-green-600 hover:underline text-sm font-medium">
                    Lihat semua kegiatan →
                </a>
            @endif
        </div>

    @else
        {{-- ── GRID KEGIATAN ─────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kegiatan as $item)
                <a href="{{ route('kegiatan.show', $item->slug ?? $item->id) }}"
                   class="group block bg-white rounded-2xl border border-gray-100 shadow-sm
                          hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    {{-- Banner --}}
                    <div class="relative h-44 overflow-hidden bg-gradient-to-br from-green-400 to-emerald-600">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 alt="{{ $item->nama }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Badge Status --}}
                        <div class="absolute top-3 left-3">
                            @php
                                $badgeColor = match($item->status) {
                                    'Berlangsung' => 'bg-white/95 text-green-700',
                                    'Persiapan'   => 'bg-white/95 text-yellow-700',
                                    'Selesai'     => 'bg-white/95 text-gray-600',
                                    'Dibatalkan'  => 'bg-white/95 text-red-600',
                                    default       => 'bg-white/95 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shadow {{ $badgeColor }}">
                                @if($item->status === 'Berlangsung')
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                                @endif
                                {{ $item->status }}
                            </span>
                        </div>

                        {{-- Kuota sisa --}}
                        @if($item->quota > 0)
                            <div class="absolute top-3 right-3">
                                @php $sisa = $item->remaining_quota; @endphp
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold shadow
                                    {{ $sisa === 0 ? 'bg-red-100 text-red-600' : 'bg-white/95 text-gray-700' }}">
                                    @if($sisa === 0)
                                        Penuh
                                    @else
                                        Sisa {{ $sisa }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Konten Card --}}
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-900 text-base mb-3 line-clamp-2 group-hover:text-green-700 transition-colors">
                            {{ $item->nama }}
                        </h3>

                        {{-- Lokasi --}}
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1.5">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">{{ $item->lokasiLahan?->nama ?? '-' }}</span>
                        </div>

                        {{-- Tanggal --}}
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') : '-' }}
                        </div>

                        {{-- Target Pohon --}}
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Target: <span class="font-medium text-gray-700">{{ number_format($item->target_pohon) }} pohon</span>
                        </div>

                        {{-- Kuota Progress (jika ada) --}}
                        @if($item->quota > 0)
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Kuota Peserta</span>
                                    <span class="font-medium">{{ $item->registered_count }}/{{ $item->quota }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all duration-500
                                        {{ $item->progressPercentage() >= 100 ? 'bg-red-400' : 'bg-green-500' }}"
                                         style="width: {{ $item->progressPercentage() }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">
                                {{ $item->realisasi_pohon }}/{{ $item->target_pohon }} pohon ditanam
                            </span>
                            <span class="text-xs font-semibold text-green-700 group-hover:underline">
                                Lihat Detail →
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- ── PAGINATION ───────────────────────────────────────────────── --}}
        @if($kegiatan->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $kegiatan->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
