@extends('layouts.auth')

@section('title', 'Masuk - Greennovate')

@section('content')
<div class="w-full max-w-sm px-6">
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
           <img src="https://ui-avatars.com/api/?name=Greennovate&background=0D8B41&color=fff&rounded=true" alt="Logo" class="h-12 w-12 object-contain" />
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Masuk ke Greennovate</h1>
        <p class="text-sm text-gray-500">Akses akun untuk ikuti kegiatan penghijauan</p>
    </div>

    {{-- Error Messages Flash --}}
    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-600 text-sm border border-red-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email / Phone --}}
        <div>
            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Email atau No HP</label>
            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus placeholder="nama@email.com / 0812xxxx" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 focus:border-green-600 outline-none transition text-sm">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-600 focus:border-green-600 outline-none transition text-sm">
            </div>
            @if (Route::has('password.request'))
                <div class="flex justify-end mt-1">
                    <a href="#" class="text-xs text-green-600 hover:underline">Lupa password?</a>
                </div>
            @endif
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat Saya</label>
        </div>

        <button type="submit" class="w-full bg-[#1b7b43] text-white font-medium py-2.5 rounded-lg hover:bg-green-700 transition">
            Masuk
        </button>

        <div class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-green-600 font-medium hover:underline">Daftar sekarang</a>
        </div>
    </form>
</div>
@endsection
