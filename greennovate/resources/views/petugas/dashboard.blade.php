@extends('layouts.auth')

@section('title', 'Petugas Dashboard - Greennovate')

@section('content')
<div class="w-full max-w-4xl px-6 mt-12">

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold uppercase tracking-wider">Petugas</span>
            <h1 class="text-3xl font-bold text-gray-900">Petugas Dashboard</h1>
        </div>
        <p class="text-gray-500">Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>. Anda login sebagai Petugas.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-yellow-50 border border-yellow-100 p-6 rounded-lg text-center">
            <p class="text-sm text-yellow-800 font-medium mb-1">Role</p>
            <p class="text-xl font-bold text-yellow-900 uppercase">Petugas</p>
        </div>
        <div class="bg-green-50 border border-green-100 p-6 rounded-lg text-center">
            <p class="text-sm text-green-800 font-medium mb-1">Status Akun</p>
            <p class="text-xl font-bold text-green-900">{{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 p-6 rounded-lg text-center">
            <p class="text-sm text-blue-800 font-medium mb-1">Kontak</p>
            <p class="text-sm font-medium text-blue-900 truncate">{{ Auth::user()->email ?? Auth::user()->phone }}</p>
        </div>
    </div>

    {{-- Menu Petugas (placeholder) --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Menu Petugas</h2>
        <p class="text-gray-400 text-sm italic">Fitur petugas akan ditambahkan di sprint berikutnya (Kelola Donasi, Validasi Kegiatan, dll).</p>
    </div>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
        </form>
    </div>
</div>
@endsection
