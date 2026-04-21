@extends('layouts.auth')

@section('title', 'Petugas Dashboard - Greennovate')

@section('content')
<div class="w-full max-w-5xl px-6 mt-8 pb-12">

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 font-medium flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-xs font-bold uppercase tracking-wider mb-2">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                Petugas Panel
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Halo, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mt-1">Selamat bertugas. Pantau dan kelola kegiatan hari ini.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-full hover:bg-red-100 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </div>

    {{-- Quick Action Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition group cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center mb-4 group-hover:bg-teal-200 transition">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Kelola Kegiatan</h3>
            <p class="text-sm text-gray-400">Tambah & kelola kegiatan lingkungan</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition group cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Peserta</h3>
            <p class="text-sm text-gray-400">Verifikasi & pantau peserta kegiatan</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition group cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-4 group-hover:bg-orange-200 transition">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Laporan</h3>
            <p class="text-sm text-gray-400">Buat & ekspor laporan kegiatan</p>
        </div>
    </div>

    {{-- Info Sesi --}}
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
            <div class="bg-teal-50 rounded-xl p-4">
                <p class="text-xs text-teal-400 font-medium mb-1">Role</p>
                <p class="font-bold text-teal-700 uppercase">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
