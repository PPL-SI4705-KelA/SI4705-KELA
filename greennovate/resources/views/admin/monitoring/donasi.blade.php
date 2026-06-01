@extends('layouts.admin')

@section('title', 'Daftar Donasi – Greennovate')
@section('page-title', 'Daftar Donasi')
@section('page-subtitle', 'Monitoring donasi masuk beserta status pembayarannya')

@section('content')
<div class="flex flex-col gap-6">

    {{-- Filter & Action Card --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
        {{-- Export Button (Kiri di Desktop, Bawah di Mobile) --}}
        <div class="w-full sm:w-auto order-2 sm:order-1" style="flex-grow: 1;">
            <a href="{{ route('admin.reports.donasi.csv', request()->all()) }}"
               class="w-full h-12 inline-flex items-center justify-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-bold px-5 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export ke CSV
            </a>
        </div>

        {{-- Filter Status (Kanan di Desktop, Atas di Mobile) --}}
        <form method="GET" action="{{ route('admin.donasi.index') }}" class="w-full sm:w-auto order-1 sm:order-2" id="filter-form" style="width: 100%; max-width: 176px;">
            {{-- Filter Status --}}
            <div class="w-full">
                <select id="status-filter" name="status" onchange="this.form.submit()"
                        class="w-full h-12 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100">
                    <option value="">Status Donasi</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="success" {{ request('status') == 'success' || request('status') == 'sukses' ? 'selected' : '' }}>Berhasil</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Daftar Donasi --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-sm">Daftar Transaksi Donasi</h2>
            <span class="text-xs text-gray-400">Total: {{ $donasis->total() }}</span>
        </div>

        @if($donasis->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">Belum ada data donasi</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID Donasi</th>
                            <th class="px-6 py-4">Nama Pengguna</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4">Tanggal Donasi</th>
                            <th class="px-6 py-4">Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach($donasis as $d)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-700">#{{ $d->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $d->user ? $d->user->name : 'N/A' }}</div>
                                <div class="text-xs text-gray-400">{{ $d->user ? $d->user->email : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-bold">
                                Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $d->created_at ? $d->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusLabel = match ($d->status) {
                                        'Pending' => 'Menunggu',
                                        'Sukses'  => 'Berhasil',
                                        'Expired' => 'Kadaluarsa',
                                        'Gagal'   => 'Gagal',
                                        default   => $d->status,
                                    };
                                    $statusColor = match ($d->status) {
                                        'Pending' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'Sukses'  => 'bg-green-50 text-green-700 border-green-100',
                                        'Expired' => 'bg-gray-50 text-gray-700 border-gray-100',
                                        'Gagal'   => 'bg-red-50 text-red-700 border-red-100',
                                        default   => 'bg-gray-50 text-gray-500 border-gray-100',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($donasis->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $donasis->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
