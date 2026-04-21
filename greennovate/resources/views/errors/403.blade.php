@extends('layouts.auth')

@section('title', '403 - Akses Ditolak | Greennovate')

@section('content')
<div class="w-full max-w-lg px-6 mt-16 pb-12 flex flex-col items-center text-center">

    {{-- Icon --}}
    <div class="w-24 h-24 rounded-full bg-red-100 flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
    </div>

    {{-- Code & Title --}}
    <p class="text-7xl font-extrabold text-red-500 mb-2">403</p>
    <h1 class="text-2xl font-bold text-gray-900 mb-3">Akses Ditolak</h1>
    <p class="text-gray-500 mb-8 leading-relaxed">
        Anda tidak memiliki izin untuk mengakses halaman ini.<br>
        @auth
            Role Anda saat ini adalah
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                @if(Auth::user()->role === 'admin') bg-purple-100 text-purple-700
                @elseif(Auth::user()->role === 'petugas') bg-teal-100 text-teal-700
                @else bg-green-100 text-green-700 @endif">
                {{ strtoupper(Auth::user()->role) }}
            </span>
            dan tidak diizinkan mengakses area ini.
        @else
            Silakan login terlebih dahulu.
        @endauth
    </p>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row items-center gap-3">
        @auth
            <a href="{{ route('dashboard') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#1b7b43] text-white px-6 py-2.5 rounded-full hover:bg-green-700 transition font-medium text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#1b7b43] text-white px-6 py-2.5 rounded-full hover:bg-green-700 transition font-medium text-sm">
                Masuk
            </a>
        @endauth

        <a href="{{ route('home') ?? '/' }}"
           onclick="history.back(); return false;"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-600 px-6 py-2.5 rounded-full hover:bg-gray-200 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

</div>
@endsection
