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
        <div class="mt-8 border-t pt-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">🌿 Kontribusi O2 Saya</h2>

    @php
        $o2Stats = \App\Models\UserO2Stat::where('user_id', Auth::id())->first();
    @endphp

    @if($o2Stats && $o2Stats->total_o2_kg_per_bulan > 0)
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-green-50 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ number_format($o2Stats->total_o2_kg_per_bulan, 1) }}</p>
            <p class="text-xs text-green-600 mt-0.5">kg O2 / bulan</p>
        </div>
        <div class="bg-teal-50 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-teal-700">{{ number_format($o2Stats->total_pohon, 2) }}</p>
            <p class="text-xs text-teal-600 mt-0.5">pohon berkontribusi</p>
        </div>
    </div>

    {{-- Badge diraih --}}
    @php
        $badges = \App\Models\UserAchievement::where('user_id', Auth::id())
            ->with('achievement')
            ->orderBy('diraih_pada', 'desc')
            ->get();
    @endphp
    @if($badges->count() > 0)
    <p class="text-sm font-semibold text-gray-600 mb-2">Badge Diraih:</p>
    <div class="flex flex-wrap gap-2">
        @foreach($badges as $ua)
        <div class="flex items-center gap-1.5 bg-white border border-green-200 rounded-full px-3 py-1.5 shadow-sm"
             title="{{ $ua->achievement->pesan_dampak }}">
            <span class="text-lg">{{ $ua->achievement->badge_icon }}</span>
            <span class="text-xs font-semibold text-gray-700">{{ $ua->achievement->nama }}</span>
        </div>
        @endforeach
    </div>
    @endif

    <a href="{{ route('achievement.index') }}"
       class="inline-block mt-3 text-sm text-green-600 hover:text-green-700 font-medium">
        Lihat semua achievement →
    </a>

    @else
    <div class="bg-gray-50 rounded-xl p-4 text-center border border-dashed border-gray-200">
        <p class="text-gray-500 text-sm">Belum ada kontribusi O2.</p>
        <a href="{{ route('kegiatan.index') }}" class="text-sm text-green-600 font-medium hover:underline mt-1 inline-block">
            Donasi ke kegiatan pohon untuk memulai →
        </a>
    </div>
    @endif
</div>
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