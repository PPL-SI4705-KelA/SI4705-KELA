@extends('layouts.admin')

@section('title', 'Kelola QR Code – Greennovate')
@section('page-title', 'Kelola QR Code')
@section('page-subtitle', 'Generate QR Code dari link yang diberikan')

@section('content')

{{-- Form Tambah --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h2 class="font-bold text-gray-800 text-sm mb-4">Generate QR Code Baru</h2>
    <form method="POST" action="{{ route('admin.qrcode.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul QR Code</label>
                <input type="text" name="judul" id="judul" required placeholder="Contoh: Absensi Kegiatan X"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0D8B41] focus:border-[#0D8B41] outline-none transition text-sm">
                @error('judul') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                <input type="url" name="link" id="link" required placeholder="https://..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0D8B41] focus:border-[#0D8B41] outline-none transition text-sm">
                @error('link') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-[#0D8B41] hover:bg-green-700 text-white text-sm font-semibold px-5 py-2 rounded-lg transition shadow-sm">
                Generate QR
            </button>
        </div>
    </form>
</div>

{{-- Daftar QR Code --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-800 text-sm">Riwayat QR Code</h2>
    </div>

    @if($qrCodes->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
            </svg>
            <p class="text-sm">Belum ada QR Code yang dibuat.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-xs text-gray-400 font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">Judul</th>
                        <th class="px-6 py-4 font-medium">Link URL</th>
                        <th class="px-6 py-4 font-medium text-center">QR Code</th>
                        <th class="px-6 py-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                    @foreach($qrCodes as $qr)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $qr->judul }}</td>
                        <td class="px-6 py-4 text-blue-600 truncate max-w-xs">
                            <a href="{{ $qr->link }}" target="_blank" class="hover:underline">{{ $qr->link }}</a>
                        </td>
                        <td class="px-6 py-4 flex justify-center">
                            {{-- Container untuk QRCode.js --}}
                            <div id="qrcode-{{ $qr->id }}" class="qrcode-container p-2 bg-white border border-gray-200 rounded-lg shadow-sm" data-link="{{ $qr->link }}"></div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="downloadQR({{ $qr->id }}, '{{ addslashes($qr->judul) }}')" 
                                        class="text-blue-500 hover:text-blue-700 p-1 bg-blue-50 hover:bg-blue-100 rounded-md transition" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('admin.qrcode.destroy', $qr->id) }}" onsubmit="return confirm('Yakin ingin menghapus QR Code ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-50 hover:bg-red-100 rounded-md transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Render semua QR Code
    const containers = document.querySelectorAll('.qrcode-container');
    containers.forEach(function (container) {
        const link = container.dataset.link;
        new QRCode(container, {
            text: link,
            width: 80,
            height: 80,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.L
        });
    });
});

// Fungsi untuk download gambar QR Code
function downloadQR(id, judul) {
    const container = document.getElementById('qrcode-' + id);
    if (!container) return;

    const canvas = container.querySelector('canvas');
    const img = container.querySelector('img');
    
    let url = '';
    
    // Prioritaskan canvas jika ada, karena rendering canvas sinkronus di browser modern
    if (canvas) {
        url = canvas.toDataURL("image/png");
    } else if (img && img.getAttribute("src")) {
        url = img.getAttribute("src");
    }

    if (url && url !== '') {
        const a = document.createElement('a');
        a.href = url;
        a.download = 'QRCode-' + judul.replace(/\s+/g, '_') + '.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    } else {
        alert('Gagal mendownload QR Code. Gambar belum siap.');
    }
}
</script>
@endpush
