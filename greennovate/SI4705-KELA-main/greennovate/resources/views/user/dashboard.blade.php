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
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard User</h1>
        <p class="text-gray-500 mb-8">Selamat datang kembali, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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

        <hr class="border-gray-100 mb-8">

        <div class="bg-gray-50 border border-gray-200/60 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#0D8B41] flex items-center justify-center text-white shadow-md shadow-green-900/10 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Kontribusi Penanaman Pohon</h3>
                    <p class="text-gray-500 text-sm mt-0.5">Ikut serta menghijaukan lahan bekas tambang dengan mengotomatisasi pemesanan bibit tanaman secara real-time.</p>
                </div>
            </div>
            <div class="w-full md:w-auto flex-shrink-0">
                <a href="{{ route('pembelian.index') }}" 
                   class="block w-full text-center bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold px-6 py-3 rounded-full text-sm transition-all shadow-md hover:shadow-lg hover:shadow-green-900/20">
                    Mulai Kontribusi
                </a>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-500 hover:underline font-medium transition-colors">Logout</button>
        </form>
    </div>
</div>
@endsection