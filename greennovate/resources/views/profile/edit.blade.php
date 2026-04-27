@extends('layouts.auth')

@section('title', 'Edit Profil')

@section('content')
<div class="w-full max-w-4xl px-6 mt-12">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow border">

        {{-- HEADER (SAMA PERSIS) --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 rounded-full bg-green-600 flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <div>
                <h1 class="text-2xl font-bold">Edit Profil</h1>
                <p class="text-gray-500 text-sm">Perbarui informasi akun Anda</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            {{-- DATA (SAMA STRUKTUR, BEDANYA JADI INPUT) --}}
            <div class="grid md:grid-cols-2 gap-6">

                {{-- NAMA --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama</p>
                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                        class="w-full border rounded p-2 focus:ring focus:ring-green-200">
                </div>

                {{-- EMAIL --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <input type="email" name="email" value="{{ Auth::user()->email }}"
                        class="w-full border rounded p-2 focus:ring focus:ring-green-200">
                </div>

                {{-- NO HP --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nomor HP</p>
                    <input type="text" name="phone" value="{{ Auth::user()->phone }}"
                        class="w-full border rounded p-2 focus:ring focus:ring-green-200">
                </div>

                {{-- ALAMAT --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">Alamat</p>
                    <input type="text" name="city" value="{{ Auth::user()->city }}"
                        class="w-full border rounded p-2 focus:ring focus:ring-green-200">
                </div>

            </div>

            {{-- ROLE (READ ONLY, BIAR KONSISTEN) --}}
            <div class="mt-6">
                <p class="text-sm text-gray-500">Role</p>
                <input type="text" value="{{ Auth::user()->role }}" disabled
                    class="w-full mt-1 border rounded p-2 bg-gray-100 text-gray-500">
            </div>

            {{-- ACTION --}}
            <div class="flex flex-wrap gap-4 mt-8">

                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Simpan Perubahan
                </button>

                <a href="{{ route('profile.index') }}"
                   class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>
@endsection