@extends('layouts.auth')

@section('title', 'Daftar - Greennovate')

@section('content')
<div class="w-full max-w-sm px-6 mb-12">
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
           <img src="https://ui-avatars.com/api/?name=G&background=0D8B41&color=fff&rounded=true" alt="Logo" class="h-10 w-10 object-contain" />
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Daftar ke Greennovate</h1>
        <p class="text-sm text-gray-500">Buat akun untuk mulai berkontribusi</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-600 text-sm border border-red-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Nama --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 focus:border-green-600 outline-none transition text-sm">
        </div>

        {{-- Email / Phone --}}
        <div>
            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Email atau No HP</label>
            <input id="login" type="text" name="login" value="{{ old('login') }}" required placeholder="nama@email.com / 0812xxxx" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 focus:border-green-600 outline-none transition text-sm">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 focus:border-green-600 outline-none transition text-sm">
            <p class="text-xs text-gray-400 mt-1">Min. 8 karakter, huruf besar/kecil, angka, simbol.</p>
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 focus:border-green-600 outline-none transition text-sm">
        </div>

        <button type="submit" class="w-full bg-[#1b7b43] text-white font-medium py-2.5 rounded-lg hover:bg-green-700 transition mt-2">
            Daftar
        </button>

        <div class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Masuk</a>
        </div>
    </form>
</div>
@endsection
