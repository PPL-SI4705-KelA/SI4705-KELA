@extends('layouts.auth')

@section('title', 'Daftar Kegiatan - Greennovate')

@section('content')
<div class="w-full max-w-5xl px-6 mt-4 pb-16">

    {{-- Header --}}
    <div class="mb-10 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold tracking-widest uppercase bg-green-100 text-green-700 mb-4">
            🌿 Program Lingkungan
        </span>
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Daftar Kegiatan</h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto">
            Temukan kegiatan penghijauan dan lingkungan yang sesuai dengan minat Anda. Bergabunglah dan berkontribusi untuk bumi yang lebih hijau.
        </p>
    </div>

    {{-- Empty State --}}
    @if($kegiatan->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Belum ada kegiatan</h2>
            <p class="text-gray-400 text-sm">Kegiatan akan segera hadir. Pantau terus halaman ini.</p>
        </div>

    @else
        {{-- Grid Kegiatan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kegiatan as $item)
                <a href="{{ route('kegiatan.show', $item->slug) }}"
                   id="card-kegiatan-{{ $item->id }}"
                   class="group block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    {{-- Gambar / Banner --}}
                    <div class="relative h-44 overflow-hidden bg-gradient-to-br from-green-400 to-emerald-600">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 alt="{{ $item->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            {{-- Placeholder SVG --}}
                            <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20C19 20 22 3 22 3c-1 2-8 2-8 2 4-2 4-6 4-6-2 0-4 2-4 4 0 0-3-7-8-7C6 7 4 9 4 9c4 0 4-2 4-2 0 0-6 3-6 7.5C2 18 4 20 6 20c2.5 0 4-2 4-2-1 3-5 5-5 5s4 0 7-4.5l1 3H14v-2.5c2.5-1 5-4 5-4-1.5 1-3 1-3 1"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                                <svg class="w-12 h-12 text-white mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Badge Status --}}
                        <div class="absolute top-3 left-3">
                            @if($item->status === 'open' && $item->isRegistrationOpen())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-white text-green-700 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                                    Dibuka
                                </span>
                            @elseif($item->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-white text-gray-500 shadow-sm">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-white text-red-600 shadow-sm">
                                    Ditutup
                                </span>
                            @endif
                        </div>

                        {{-- Sisa Kuota --}}
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-black/30 text-white backdrop-blur-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                </svg>
                                {{ $item->remaining_quota }} slot
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        {{-- Judul --}}
                        <h2 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-green-700 transition-colors line-clamp-2">
                            {{ $item->title }}
                        </h2>

                        {{-- Lokasi --}}
                        <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-3">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">{{ $item->location }}</span>
                        </div>

                        {{-- Deskripsi singkat --}}
                        <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                            {{ $item->description }}
                        </p>

                        {{-- Progress Kuota --}}
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                                <span>{{ $item->registered_count }} peserta terdaftar</span>
                                <span>Kuota: {{ $item->quota }}</span>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700
                                    {{ $item->progressPercentage() >= 90 ? 'bg-red-400' : ($item->progressPercentage() >= 60 ? 'bg-yellow-400' : 'bg-green-500') }}"
                                    style="width: {{ $item->progressPercentage() }}%">
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="flex items-center gap-1.5 text-xs text-gray-400 border-t border-gray-50 pt-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $item->start_date->translatedFormat('d M Y') }} – {{ $item->end_date->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- CTA Footer --}}
                    <div class="px-5 pb-5">
                        <div class="flex items-center justify-between text-sm font-medium text-green-700 group-hover:text-green-800">
                            <span>Lihat Detail</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection
