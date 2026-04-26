@extends('layouts.auth')

@section('title', 'Admin Dashboard - Greennovate')

@section('content')
<div class="w-full max-w-5xl px-6 mt-12">

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase tracking-wider">Admin</span>
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        </div>
        <p class="text-gray-500">Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>. Anda login sebagai Administrator.</p>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-100 p-5 rounded-lg text-center">
            <p class="text-sm text-blue-700 font-medium mb-1">Total Pengguna</p>
            <p class="text-3xl font-bold text-blue-900">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-purple-50 border border-purple-100 p-5 rounded-lg text-center">
            <p class="text-sm text-purple-700 font-medium mb-1">Total Petugas</p>
            <p class="text-3xl font-bold text-purple-900">{{ $stats['total_petugas'] }}</p>
        </div>
        <div class="bg-green-50 border border-green-100 p-5 rounded-lg text-center">
            <p class="text-sm text-green-700 font-medium mb-1">Akun Aktif</p>
            <p class="text-3xl font-bold text-green-900">{{ $stats['total_aktif'] }}</p>
        </div>
        <div class="bg-red-50 border border-red-100 p-5 rounded-lg text-center">
            <p class="text-sm text-red-700 font-medium mb-1">Akun Nonaktif</p>
            <p class="text-3xl font-bold text-red-900">{{ $stats['total_nonaktif'] }}</p>
        </div>
    </div>

    {{-- Pengguna terbaru --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengguna Terbaru</h2>
        @if($users->isEmpty())
            <p class="text-gray-400 text-sm">Belum ada pengguna terdaftar.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase border-b">
                        <tr>
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">Email / HP</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr>
                            <td class="py-2 pr-4 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="py-2 pr-4 text-gray-500">{{ $user->email ?? $user->phone }}</td>
                            <td class="py-2 pr-4">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-2 text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
        </form>
    </div>
</div>
@endsection
