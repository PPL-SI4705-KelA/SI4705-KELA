@extends('layouts.landing')

@section('title', 'Informasi Pembayaran')

@section('content')

<div class="pt-32 pb-16 bg-gray-50 min-h-screen">

    <div class="max-w-3xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-lg p-8">

            <h1 class="text-3xl font-bold text-[#0D8B41] mb-6">
                Informasi Pembayaran
            </h1>

            <div class="space-y-4">

                <div>
                    <strong>Kode Transaksi</strong>
                    <br>
                    {{ $pembelian->kode_transaksi }}
                </div>

                <div>
                    <strong>Item Kontribusi</strong>
                    <br>
                    {{ $pembelian->nama_item }}
                </div>

                <div>
                    <strong>Total Pembayaran</strong>
                    <br>
                    Rp {{ number_format($pembelian->total_harga,0,',','.') }}
                </div>

            </div>

            <hr class="my-6">

            <div class="bg-green-50 border border-green-200 rounded-xl p-5">

                <h3 class="font-bold text-green-700 mb-3">
                    Transfer Pembayaran
                </h3>

                <div class="space-y-2">

                    <div>
                        <strong>Bank:</strong>
                        BCA
                    </div>

                    <div>
                        <strong>No Rekening:</strong>
                        1234567890
                    </div>

                    <div>
                        <strong>Atas Nama:</strong>
                        Greennovate Indonesia
                    </div>

                </div>

            </div>

            <form
                action="{{ route('pembelian.upload-bukti', $pembelian->id) }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-8">

                @csrf

                <label class="block font-semibold mb-2">
                    Upload Bukti Transfer
                </label>

               <input
                    type="file"
                    name="bukti_transfer"
                    id="bukti_transfer"
                    accept=".jpg,.jpeg,.png"
                    required
                    class="w-full border rounded-lg p-3">

                <p id="error-file"
                class="text-red-500 text-sm mt-2 hidden">
                    Bukti transfer harus berupa file JPG atau PNG.
                </p>

                <button
                    type="submit"
                    class="mt-5 bg-[#0D8B41] hover:bg-[#086432] text-white px-6 py-3 rounded-xl">
                    Kirim Bukti Pembayaran
                </button>

            </form>

        </div>

    </div>

</div>

<script>
document.getElementById('bukti_transfer').addEventListener('change', function() {

    const file = this.files[0];

    if (!file) return;

    const allowedTypes = [
        'image/jpeg',
        'image/png'
    ];

    const errorText = document.getElementById('error-file');

    if (!allowedTypes.includes(file.type)) {

        errorText.classList.remove('hidden');

        this.value = '';

        alert('File ditolak! Hanya JPG atau PNG yang diperbolehkan.');

    } else {

        errorText.classList.add('hidden');

    }

});
</script>

@endsection