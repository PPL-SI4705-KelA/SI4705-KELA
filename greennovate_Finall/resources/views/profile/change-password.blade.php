@extends('layouts.auth')

@section('title', 'Ubah Password')

@section('content')
<div class="w-full max-w-xl px-6 mt-12">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border">
        <h1 class="text-xl font-bold mb-6">Ubah Password</h1>

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf

            <div class="mb-4">
                <label>Password Lama</label>
                <input type="password" name="old_password" class="w-full mt-1 border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Password Baru</label>
                <input type="password" name="new_password" class="w-full mt-1 border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Konfirmasi Password</label>
                <input type="password" name="new_password_confirmation" class="w-full mt-1 border rounded p-2">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan Password
            </button>
        </form>
    </div>

</div>
@endsection