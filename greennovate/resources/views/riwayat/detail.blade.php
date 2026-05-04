@extends('layouts.auth')

@section('title', __('Detail Riwayat') . ' - Greennovate')

@section('content')
<div class="w-full max-w-4xl px-6 mt-6 mb-16">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-green-700 transition">{{ __('Dashboard') }}</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('riwayat.index') }}" class="hover:text-green-700 transition">{{ __('Riwayat Partisipasi') }}</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-800 font-medium truncate max-w-[200px]">
            @if($type === 'kegiatan')
                {{ $item->kegiatan?->nama ?? $item->nama_lengkap }}
            @elseif($type === 'donasi')
                {{ __('Donasi') }} {{ $item->formatted_jumlah }}
            @else
                {{ $item->nama_produk }}
            @endif
        </span>
    </nav>

    {{-- Error Toast --}}
    @if(session('error'))
        <div id="error-toast" class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 text-red-700 border border-red-200 shadow-sm animate-slide-in">
            <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            {{-- Type Icon --}}
            <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center
                @if($type === 'kegiatan') bg-green-100 text-green-600
                @elseif($type === 'donasi') bg-blue-100 text-blue-600
                @else bg-amber-100 text-amber-600
                @endif
            ">
                @if($type === 'kegiatan')
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                @elseif($type === 'donasi')
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                @else
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                @endif
            </div>

            {{-- Title & Meta --}}
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">
                    @if($type === 'kegiatan')
                        {{ $item->kegiatan?->nama ?? $item->nama_lengkap }}
                    @elseif($type === 'donasi')
                        {{ __('Donasi') }} {{ $item->formatted_jumlah }}
                    @else
                        {{ $item->nama_produk }}
                    @endif
                </h1>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span class="text-xs text-gray-400">{{ $item->created_at->format('d M Y, H:i') }}</span>
                    <span class="text-gray-300">·</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($type === 'kegiatan') bg-green-50 text-green-700
                        @elseif($type === 'donasi') bg-blue-50 text-blue-700
                        @else bg-amber-50 text-amber-700
                        @endif
                    ">
                        @if($type === 'kegiatan') {{ __('Kegiatan') }}
                        @elseif($type === 'donasi') {{ __('Donasi') }}
                        @else {{ __('Pembelian') }}
                        @endif
                    </span>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusColor = $item->status_color;
                $statusLabel = $item->status_label;
                $badgeColors = [
                    'green'  => 'bg-green-100 text-green-700 ring-green-600/10',
                    'yellow' => 'bg-yellow-100 text-yellow-700 ring-yellow-600/10',
                    'red'    => 'bg-red-100 text-red-700 ring-red-600/10',
                    'blue'   => 'bg-blue-100 text-blue-700 ring-blue-600/10',
                    'gray'   => 'bg-gray-100 text-gray-600 ring-gray-600/10',
                ];
                $badgeClass = $badgeColors[$statusColor] ?? $badgeColors['gray'];
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold ring-1 ring-inset {{ $badgeClass }} flex-shrink-0">
                <span class="w-1.5 h-1.5 rounded-full
                    @if($statusColor === 'green') bg-green-500
                    @elseif($statusColor === 'yellow') bg-yellow-500
                    @elseif($statusColor === 'red') bg-red-500
                    @elseif($statusColor === 'blue') bg-blue-500
                    @else bg-gray-400
                    @endif
                "></span>
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    {{-- Detail Content --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Detail Informasi') }}
            </h2>
        </div>

        <div class="p-6 md:p-8">
            @if($type === 'kegiatan')
                {{-- KEGIATAN DETAIL --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Nama Kegiatan') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->kegiatan?->nama ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Lokasi') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->kegiatan?->lokasiLahan?->nama ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Tanggal Kegiatan') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->kegiatan?->tanggal?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Nama Pendaftar') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->nama_lengkap }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('No. HP') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->no_hp }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Alamat') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->alamat }}</p>
                    </div>
                </div>
                @if($item->catatan)
                    <div class="mt-5 p-4 rounded-xl bg-blue-50 border border-blue-100">
                        <p class="text-xs text-blue-600 font-medium mb-1">{{ __('Catatan Admin') }}</p>
                        <p class="text-sm text-blue-800">{{ $item->catatan }}</p>
                    </div>
                @endif

            @elseif($type === 'donasi')
                {{-- DONASI DETAIL --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Jumlah Donasi') }}</p>
                        <p class="text-lg font-bold text-green-700">{{ $item->formatted_jumlah }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Metode Bayar') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->metode_bayar }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Kode Transaksi') }}</p>
                        <p class="text-sm font-semibold text-gray-900 font-mono">{{ $item->kode_transaksi ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Tanggal') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @if($item->pesan)
                    <div class="mt-5 p-4 rounded-xl bg-green-50 border border-green-100">
                        <p class="text-xs text-green-600 font-medium mb-1">{{ __('Pesan / Doa') }}</p>
                        <p class="text-sm text-green-800 italic">"{{ $item->pesan }}"</p>
                    </div>
                @endif
                @if($item->catatan_admin)
                    <div class="mt-3 p-4 rounded-xl bg-blue-50 border border-blue-100">
                        <p class="text-xs text-blue-600 font-medium mb-1">{{ __('Catatan Admin') }}</p>
                        <p class="text-sm text-blue-800">{{ $item->catatan_admin }}</p>
                    </div>
                @endif

            @else
                {{-- PEMBELIAN DETAIL --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Nama Produk') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->nama_produk }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Kategori') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->kategori ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Jumlah') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->jumlah_item }} item</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Harga Satuan') }}</p>
                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Total Harga') }}</p>
                        <p class="text-lg font-bold text-green-700">{{ $item->formatted_total }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Metode Bayar') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->metode_bayar }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Kode Transaksi') }}</p>
                        <p class="text-sm font-semibold text-gray-900 font-mono">{{ $item->kode_transaksi ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Tanggal') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @if($item->catatan_admin)
                    <div class="mt-5 p-4 rounded-xl bg-blue-50 border border-blue-100">
                        <p class="text-xs text-blue-600 font-medium mb-1">{{ __('Catatan Admin') }}</p>
                        <p class="text-sm text-blue-800">{{ $item->catatan_admin }}</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Bukti & Dokumentasi Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                {{ __('Bukti & Dokumentasi') }}
            </h2>
        </div>

        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- QR Code --}}
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-3">{{ __('QR Code') }}</p>
                    @if(($type === 'kegiatan' && $item->hasQrCode()) || ($type === 'pembelian' && $item->hasQrCode()))
                        <div class="p-4 bg-white border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center">
                            <img src="{{ Storage::url($item->qr_code) }}" alt="QR Code" class="max-w-[180px] max-h-[180px]">
                        </div>
                    @else
                        <div class="p-6 bg-gray-50 border border-gray-200 rounded-xl text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            </svg>
                            <p class="text-xs text-gray-400">{{ __('QR Code belum tersedia') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Dokumentasi --}}
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-3">{{ __('Dokumentasi') }}</p>
                    @if(($type === 'kegiatan' && $item->hasDokumentasi()) || ($type === 'pembelian' && $item->hasDokumentasi()))
                        <a href="{{ route('riwayat.download', ['type' => $type, 'id' => $item->id]) }}"
                           class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition group">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-green-700">{{ __('Unduh Dokumentasi') }}</p>
                                <p class="text-xs text-green-600">{{ __('Klik untuk mengunduh file') }}</p>
                            </div>
                        </a>
                    @else
                        <div class="p-6 bg-gray-50 border border-gray-200 rounded-xl text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <p class="text-xs text-gray-400">{{ __('Dokumentasi belum tersedia') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status Info Box: Menunggu/Pending --}}
            @if(($type === 'kegiatan' && $item->status === 'Menunggu') || (in_array($type, ['donasi', 'pembelian']) && $item->status === 'Pending'))
                <div class="mt-6 flex items-start gap-3 p-4 rounded-xl bg-yellow-50 border border-yellow-200">
                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">{{ __('Sedang Diproses') }}</p>
                        <p class="text-xs text-yellow-700 mt-0.5">{{ __('Dokumentasi sedang diproses oleh admin. Silakan cek kembali nanti.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Back Button --}}
    <a href="{{ route('riwayat.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-700 transition group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('Kembali ke Riwayat') }}
    </a>
</div>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.4s ease-out;
    }
</style>
@endsection
