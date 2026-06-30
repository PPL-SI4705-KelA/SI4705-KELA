@extends('layouts.landing')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Konfirmasi Donasi Anda</h1>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-2 bg-green-600"></div>
            
            <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Ringkasan Transaksi</h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Program Kegiatan</span>
                    <span class="text-gray-900 font-semibold text-base">{{ $kegiatan->nama }}</span>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Nama Donatur</span>
                        <span class="text-gray-800 font-medium">{{ $data['nama_donatur'] }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Nomor HP</span>
                        <span class="text-gray-800 font-medium">{{ $data['nomor_hp'] }}</span>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                    <span class="text-xs font-bold text-green-700 uppercase tracking-wider block">Total Nominal Donasi</span>
                    <span class="text-2xl font-extrabold text-green-600 block mt-1">
                        Rp {{ number_format($data['jumlah'], 0, ',', '.') }}
                    </span>
                </div>

                @if($data['catatan'])
                <div class="bg-gray-50 rounded-lg p-3 italic text-gray-600 border border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block not-italic mb-1">Catatan/Harapan:</span>
                    "{{ $data['catatan'] }}"
                </div>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <!-- <p class="text-xs text-gray-400 text-center mb-4">
                    Sistem akan memverifikasi status operasional kegiatan secara real-time setelah Anda menekan tombol di bawah.
                </p> -->
                <p class="text-xs text-gray-400 text-center mb-4">
                    <!-- Setelah melanjutkan, sistem akan membuat transaksi dengan status
                    <strong>Menunggu Pembayaran</strong>. -->
                    Anda diminta mengunggah bukti pembayaran dalam waktu 10 menit.
                </p>

                <form action="{{ route('donations.submit') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow transition duration-200">
                        Lanjut Pembayaran
                    </button>
                </form>

                <a href="{{ route('donations.index') }}" class="block text-center text-sm font-medium text-gray-500 hover:text-green-600 mt-4 transition underline">
                    Kembali & Ubah Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection