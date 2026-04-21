@extends('layouts.auth')

@section('title', 'Admin Dashboard - Greennovate')

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
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold uppercase tracking-wider mb-2">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                Admin Panel
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mt-1">Kelola seluruh sistem Greennovate dari sini.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-full hover:bg-red-100 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total User</p>
            <p class="text-4xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">role: user</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Petugas</p>
            <p class="text-4xl font-bold text-green-600">{{ $stats['total_petugas'] }}</p>
            <p class="text-xs text-gray-400 mt-1">role: petugas</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Admin</p>
            <p class="text-4xl font-bold text-purple-600">{{ $stats['total_admin'] }}</p>
            <p class="text-xs text-gray-400 mt-1">role: admin</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Akun Nonaktif</p>
            <p class="text-4xl font-bold text-red-500">{{ $stats['inactive_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">is_active: false</p>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
            Informasi Sesi
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-medium mb-1">Nama</p>
                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-medium mb-1">Kontak</p>
                <p class="font-semibold text-gray-800 truncate">{{ Auth::user()->email ?? Auth::user()->phone }}</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4">
                <p class="text-xs text-purple-400 font-medium mb-1">Role</p>
                <p class="font-bold text-purple-700 uppercase">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
