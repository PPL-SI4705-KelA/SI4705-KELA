@extends('layouts.petugas')

@section('title', 'Input Realisasi Penanaman - Greennovate')
@section('header', 'Input Realisasi')

@section('content')
<div class="max-w-xl mx-auto">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-[#1a8245] transition-colors font-medium">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 font-medium">Input Realisasi</span>
    </nav>

    {{-- Form Card --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#1a8245] to-[#15803d] p-5 text-white">
            <h2 class="font-bold text-lg">Catat Realisasi Penanaman</h2>
            <p class="text-green-100 text-xs mt-1">Isi detail jumlah pohon yang berhasil ditanam secara akurat</p>
        </div>

        {{-- Form Body --}}
        <form action="{{ route('petugas.realisasi.store') }}" method="POST" id="realisasiForm" class="p-6 space-y-5">
            @csrf

            {{-- Kegiatan Dropdown --}}
            <div>
                <label for="kegiatan_id" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Pilih Kegiatan <span class="text-red-400">*</span></label>
                <select name="kegiatan_id" id="kegiatan_id" required
                        class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                    <option value="">-- Pilih Kegiatan --</option>
                    @foreach($kegiatans as $keg)
                        <option value="{{ $keg->id }}" {{ (old('kegiatan_id') == $keg->id || $selectedKegiatanId == $keg->id) ? 'selected' : '' }}>
                            {{ $keg->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kegiatan_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Kegiatan Box (Dynamic via JS) --}}
            <div id="kegiatanInfoBox" class="hidden bg-gray-50 border border-gray-100 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400 font-medium">Lokasi:</span>
                    <span id="infoLokasi" class="text-gray-700 font-semibold text-right"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400 font-medium">Target Pohon:</span>
                    <span id="infoTarget" class="text-gray-700 font-semibold"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400 font-medium">Progres Saat Ini:</span>
                    <span id="infoProgres" class="text-gray-700 font-semibold"></span>
                </div>
            </div>

            {{-- Jenis Pohon Dropdown --}}
            <div>
                <label for="jenis_pohon_id" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Jenis Pohon <span class="text-red-400">*</span></label>
                <select name="jenis_pohon_id" id="jenis_pohon_id" required
                        class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                    <option value="">-- Pilih Jenis Pohon --</option>
                    @foreach($jenisPohons as $pohon)
                        <option value="{{ $pohon->id }}" {{ old('jenis_pohon_id') == $pohon->id ? 'selected' : '' }}>
                            {{ $pohon->nama }} ({{ $pohon->nama_latin ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('jenis_pohon_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah Tertanam Input --}}
            <div>
                <label for="jumlah_tertanam" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Jumlah Pohon Tertanam <span class="text-red-400">*</span></label>
                <input type="number" name="jumlah_tertanam" id="jumlah_tertanam" required
                       value="{{ old('jumlah_tertanam') }}" placeholder="Masukkan jumlah pohon (>= 0)"
                       class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                @error('jumlah_tertanam')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catatan Textarea --}}
            <div>
                <label for="catatan" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Catatan Lapangan <span class="text-gray-300">(opsional)</span></label>
                <textarea name="catatan" id="catatan" rows="3" maxlength="500" placeholder="Tambahkan catatan hasil penanaman..."
                          class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all resize-none">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-3 border-t border-gray-50">
                <a href="{{ route('petugas.dashboard') }}"
                   class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 font-semibold text-sm rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center min-h-[44px]">
                    Batal
                </a>
                <button type="submit" id="btnSubmit"
                        class="flex-1 px-4 py-2.5 bg-[#1a8245] text-white font-semibold text-sm rounded-xl hover:bg-green-800 transition-colors flex items-center justify-center gap-2 min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Realisasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data kegiatan diparsing ke format JS
    const kegiatansData = @json($kegiatans);

    const selectKegiatan = document.getElementById('kegiatan_id');
    const infoBox = document.getElementById('kegiatanInfoBox');
    const infoLokasi = document.getElementById('infoLokasi');
    const infoTarget = document.getElementById('infoTarget');
    const infoProgres = document.getElementById('infoProgres');

    function updateKegiatanInfo() {
        const id = selectKegiatan.value;
        const kegiatan = kegiatansData.find(k => k.id == id);
        if (kegiatan) {
            infoLokasi.textContent = kegiatan.lokasi_lahan ? kegiatan.lokasi_lahan.alamat : '-';
            infoTarget.textContent = new Intl.NumberFormat('id-ID').format(kegiatan.target_pohon) + ' Pohon';
            infoProgres.textContent = new Intl.NumberFormat('id-ID').format(kegiatan.realisasi_pohon) + ' Pohon';
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }
    }

    selectKegiatan.addEventListener('change', updateKegiatanInfo);

    // Panggil sekali untuk inisialisasi jika ada old value atau pre-selected
    if (selectKegiatan.value) {
        updateKegiatanInfo();
    }

    // Submit listener untuk warning confirmation (AC-5)
    document.getElementById('realisasiForm').addEventListener('submit', function(e) {
        const id = selectKegiatan.value;
        const kegiatan = kegiatansData.find(k => k.id == id);
        if (kegiatan) {
            const jumlahInput = parseInt(document.getElementById('jumlah_tertanam').value) || 0;
            if (jumlahInput > kegiatan.target_pohon) {
                if (!confirm("Jumlah melebihi target kegiatan. Yakin ingin menyimpan?")) {
                    e.preventDefault();
                    return false;
                }
            }
        }
    });
</script>
@endpush
