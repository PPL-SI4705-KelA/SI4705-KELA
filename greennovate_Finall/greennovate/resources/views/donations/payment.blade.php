@extends('layouts.landing')

@section('content')

<div class="container mx-auto px-4 pt-24 py-8">
    <div class="max-w-md mx-auto">

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">

        <!-- Header Invoice -->
        <div class="bg-gray-800 px-6 py-4 text-white flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold">Invoice Pembayaran</h2>
                <p class="text-xs text-gray-400">
                    ID: {{ $donasi->kode_transaksi }}
                </p>
            </div>

            <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                {{ $donasi->status }}
            </span>
        </div>

        <div class="p-6">

            <!-- Detail Donasi -->
            <div class="mb-6">
                <span class="text-xs font-bold text-gray-400 uppercase block tracking-wider mb-1">
                    Alokasi Kegiatan
                </span>

                <p class="text-gray-800 font-semibold">
                    {{ $donasi->kegiatan->nama ?? $donasi->nama_donasi }}
                </p>
            </div>

            <!-- Nominal -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center mb-6">
                <span class="text-sm text-gray-500 block mb-1">
                    Total Donasi
                </span>

                <span class="text-3xl font-black text-gray-900">
                    Rp {{ number_format($donasi->jumlah, 0, ',', '.') }}
                </span>
            </div>

            <!-- Rekening Tujuan -->
            <div class="border border-green-100 bg-green-50 rounded-xl p-4 mb-6">

                <h3 class="font-bold text-green-800 mb-3">
                    Instruksi Pembayaran
                </h3>

                <div class="bg-white rounded-lg border border-green-200 p-4">

                    <div class="mb-3">
                        <p class="text-sm text-gray-500">
                            Bank Tujuan
                        </p>

                        <p class="font-bold text-lg text-gray-800">
                            Bank BCA
                        </p>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-500">
                            Nomor Rekening
                        </p>

                        <p class="font-mono font-bold text-xl text-green-700">
                            1234567890
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Atas Nama
                        </p>

                        <p class="font-semibold text-gray-800">
                            Greennovate Indonesia
                        </p>
                    </div>
                </div>

                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-sm text-yellow-800">
                        Silakan transfer sesuai nominal donasi.
                        Setelah transfer berhasil, unggah bukti pembayaran.
                        Bukti pembayaran harus diunggah dalam waktu
                        <strong>10 menit</strong>.
                        <!-- Jika melewati batas waktu tersebut,
                        transaksi akan otomatis berubah menjadi
                        <strong>Expired</strong>. -->
                    </p>
                </div>

            </div>

            <!-- Upload Bukti Pembayaran -->
            @if($donasi->status === 'pending')

                <form
                    action="{{ route('donations.upload-proof', $donasi->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Upload Bukti Pembayaran
                        </label>

                        <input
                            type="file"
                            name="bukti_pembayaran"
                            accept=".jpg,.jpeg,.png"
                            required
                            class="w-full border border-gray-300 rounded-lg p-2"
                        >

                        <p class="text-xs text-gray-500 mt-1">
                            Format yang diperbolehkan:
                            JPG, JPEG, PNG
                        </p>

                        @error('bukti_pembayaran')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow transition duration-200"
                    >
                        Kirim Bukti Pembayaran
                    </button>
                </form>

            @endif

            <!-- Status Verifikasi -->
            @if($donasi->status === 'menunggu_verifikasi')

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="font-semibold text-blue-800">
                        Bukti pembayaran berhasil dikirim.
                    </p>

                    <p class="text-sm text-blue-700 mt-1">
                        Donasi Anda sedang menunggu verifikasi admin.
                    </p>
                </div>

            @endif

            <!-- Expired -->
            @if($donasi->status === 'expired')

                <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-center">
                    <p class="font-semibold text-gray-700">
                        Transaksi Kedaluwarsa
                    </p>

                    <p class="text-sm text-gray-600 mt-1">
                        Batas waktu upload bukti pembayaran telah berakhir.
                    </p>
                </div>

            @endif

        </div>
    </div>
</div>
```

</div>
@endsection
