@extends('layouts.auth')

@section('title', 'Dashboard - Greennovate')

@section('content')
<div class="w-full max-w-5xl px-6 mt-8 pb-12">

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wider mb-2">
                User Panel
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mt-1">Ikuti kegiatan dan pantau progres lingkunganmu.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-full hover:bg-red-100 transition text-sm font-medium">
                Logout
            </button>
        </form>
    </div>

    {{-- Feature Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Kegiatan Tersedia</h3>
            <p class="text-sm text-gray-400">Temukan & daftar kegiatan lingkungan</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Riwayat Kegiatan</h3>
            <p class="text-sm text-gray-400">Lihat kegiatan yang sudah diikuti</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Poin & Reward</h3>
            <p class="text-sm text-gray-400">Pantau poin kontribusi lingkunganmu</p>
        </div>
    </div>

    {{-- Info Akun --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-medium mb-1">Nama</p>
                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-medium mb-1">Kontak</p>
                <p class="font-semibold text-gray-800 truncate">{{ Auth::user()->email ?? Auth::user()->phone }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4">
                <p class="text-xs text-green-400 font-medium mb-1">Status</p>
                <p class="font-bold text-green-700">{{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
