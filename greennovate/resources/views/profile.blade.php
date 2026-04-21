@extends('layouts.auth')

@section('title', 'Profil')

@section('content')
<div class="w-full max-w-3xl px-6 mt-12">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border">
        <h1 class="text-2xl font-bold mb-6">Profil Saya</h1>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <div class="mb-4">
                <label class="text-sm">Nama</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}"
                    class="w-full mt-1 border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="text-sm">Email</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}"
                    class="w-full mt-1 border rounded p-2">
            </div>

            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Simpan Perubahan
            </button>
        </form>

        <!-- Tombol ke halaman ubah password -->
        <div class="mt-6">
            <a href="{{ route('profile.password.form') }}"
               class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Ubah Password
            </a>
        </div>
    </div>

</div>
@endsection