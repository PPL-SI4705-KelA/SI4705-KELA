@extends('layouts.landing')

@section('title', 'Instruksi Pembayaran')

@section('content')
<div class="pt-24 pb-16 max-w-3xl mx-auto px-6">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Instruksi Pembayaran</h1>
        <p class="text-gray-500">Selesaikan pembayaran Anda untuk melanjutkan proses transaksi.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden mb-6">
        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Transaksi</h2>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500">Jenis Transaksi</span>
                <span class="font-semibold text-gray-900 capitalize">{{ $tipe }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500">Item / Nama Donasi</span>
                <span class="font-semibold text-gray-900">{{ $nama_item }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-gray-500">Nominal yang harus dibayar</span>
                <span class="font-bold text-[#0D8B41] text-lg">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Tujuan Transfer</h2>
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mb-8">
                <p class="text-sm text-blue-800 mb-2">Silakan transfer sesuai nominal di atas ke rekening berikut:</p>
                <div class="font-mono text-xl font-bold text-blue-900 mb-1 tracking-wide">{{ $nomor_rekening }}</div>
                <p class="text-xs text-blue-700">Gunakan metode transfer bank, m-banking, atau ATM.</p>
            </div>

            <h2 class="text-lg font-bold text-gray-900 mb-4">Konfirmasi Pembayaran</h2>
            
            @if(in_array($status, ['Menunggu Konfirmasi', 'Sukses', 'Expired', 'Gagal']))
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                    @if($status === 'Menunggu Konfirmasi')
                        <svg class="w-12 h-12 text-[#0D8B41] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-md font-bold text-gray-900 mb-1">Menunggu Konfirmasi</h3>
                        <p class="text-sm text-gray-500">Bukti pembayaran Anda sedang diverifikasi oleh Admin.</p>
                    @else
                        <h3 class="text-md font-bold text-gray-900 mb-1">Status Transaksi: {{ $status }}</h3>
                        <p class="text-sm text-gray-500">Transaksi Anda saat ini tidak dapat mengunggah bukti pembayaran lagi.</p>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('riwayat.index') }}" class="text-[#0D8B41] font-semibold text-sm hover:underline">Kembali ke Riwayat</a>
                    </div>
                </div>
            @else
                <form action="{{ route('pembayaran.upload', ['tipe' => $tipe, 'id' => $id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if($status === 'Ditolak')
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <strong class="block font-bold">Bukti Ditolak</strong>
                                <span class="text-sm">Bukti transfer Anda ditolak. Silakan unggah ulang bukti yang valid (foto jelas, tidak terpotong, nominal sesuai).</span>
                            </div>
                        </div>
                    @endif

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah Bukti Transfer <span class="text-red-500">*</span></label>
                        <input type="file" name="bukti_transfer" accept=".jpg,.jpeg,.png" required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#0D8B41]/10 file:text-[#0D8B41] hover:file:bg-[#0D8B41]/20 border border-gray-200 rounded-xl focus:outline-none focus:border-[#0D8B41] focus:ring-1 focus:ring-[#0D8B41]">
                        @error('bukti_transfer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-2">Format yang diizinkan: JPG, JPEG, PNG. Ukuran maksimal: 2MB.</p>
                    </div>

                    <button type="submit" class="w-full bg-[#0D8B41] hover:bg-[#085c2b] text-white font-bold py-3 px-4 rounded-xl transition duration-200">
                        Kirim Bukti Pembayaran
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
