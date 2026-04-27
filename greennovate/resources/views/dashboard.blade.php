@extends('layouts.auth')

@section('title', 'Dashboard - Greennovate')

@section('content')
<div class="w-full max-w-4xl px-6 mt-12">
    
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-500 mb-8">Selamat datang kembali, {{ Auth::user()->name }}!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 border border-green-100 p-6 rounded-lg text-center">
                <p class="text-sm text-green-800 font-medium mb-1">Status Akun</p>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-200 text-green-800 text-sm font-semibold">
                    {{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-100 p-6 rounded-lg text-center">
                <p class="text-sm text-blue-800 font-medium mb-1">Role Anda</p>
                <h3 class="text-xl font-bold text-blue-900 uppercase">{{ Auth::user()->role }}</h3>
            </div>

            <div class="bg-orange-50 border border-orange-100 p-6 rounded-lg text-center">
                <p class="text-sm text-orange-800 font-medium mb-1">Informasi Kontak</p>
                <p class="text-sm text-orange-900 truncate">{{ Auth::user()->email ?? Auth::user()->phone }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
