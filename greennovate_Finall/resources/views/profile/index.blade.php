@extends('layouts.auth')

@section('title', 'Profil')

@section('content')
<div class="w-full max-w-4xl px-6 mt-12">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow border">

        {{-- HEADER --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 rounded-full bg-green-600 flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <div>
                <h1 class="text-2xl font-bold">Profil Saya</h1>
                <p class="text-gray-500 text-sm">Informasi akun Anda</p>
            </div>
        </div>

        {{-- DATA --}}
        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">Nama</p>
                <p class="font-semibold">{{ Auth::user()->name }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-semibold">{{ Auth::user()->email }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nomor HP</p>
                <p class="font-semibold">{{ Auth::user()->phone ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Alamat</p>
                <p class="font-semibold">{{ Auth::user()->city ?? '-' }}</p>
            </div>

        </div>

        {{-- ROLE --}}
        <div class="mt-6">
            <p class="text-sm text-gray-500">Role</p>
            <p class="font-semibold uppercase">{{ Auth::user()->role }}</p>
        </div>

        {{-- ACTION --}}
        <div class="flex flex-wrap gap-4 mt-8">

            {{-- Edit Profil --}}
            <a href="{{ route('profile.edit') }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Edit Profil
            </a>

            {{-- Ubah Password --}}
            <a href="{{ route('profile.password.form') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Ubah Password
            </a>

        </div>

    </div>

</div>
@endsection