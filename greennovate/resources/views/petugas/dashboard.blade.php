@extends('layouts.petugas')

@section('title', 'Dashboard Petugas - Greennovate')
@section('header', 'Kegiatan Saya')

@section('content')
<div class="max-w-6xl">

    {{-- Greeting Card (AC-1) --}}
    <div class="bg-gradient-to-r from-[#1a8245] to-[#15803d] p-6 rounded-2xl shadow-md mb-8 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute right-20 bottom-0 w-24 h-24 bg-white/5 rounded-full translate-y-1/2"></div>
        <div class="relative z-10">
            <h1 class="text-2xl font-bold mb-1">{{ $greeting }}, {{ $user->name }} 👋</h1>
            <p class="text-green-100 text-sm">
                {{ now()->translatedFormat('l, d F Y • H:i') }} WIB
            </p>
            <div class="mt-3 flex items-center gap-2 text-sm text-green-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Petugas Lapangan · {{ $user->city ?? 'Indonesia' }}
            </div>
        </div>
    </div>

    {{-- Section: Kegiatan Aktif (AC-2) --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-1">Kegiatan Aktif</h2>
        <p class="text-sm text-gray-500">Kegiatan yang sedang berlangsung dan membutuhkan pencatatan</p>
    </div>

    {{-- Cards Grid (AC-2, AC-9) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        @forelse($kegiatanAktif as $kegiatan)
            @php
                $percentage = $kegiatan->target_pohon > 0
                    ? min(round(($kegiatan->realisasi_pohon / $kegiatan->target_pohon) * 100), 100)
                    : 0;
                // AC-8: Progress bar color
                if ($percentage < 50) { $progressColor = '#f97316'; $progressBg = '#fff7ed'; }
                elseif ($percentage <= 75) { $progressColor = '#eab308'; $progressBg = '#fefce8'; }
                else { $progressColor = '#22c55e'; $progressBg = '#f0fdf4'; }
                // AC-3: Highlight low progress
                $isLowProgress = $percentage < 50;
                // AC-8: Status badge
                $statusConfig = match($kegiatan->status) {
                    'Berlangsung' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z'],
                    'Persiapan'   => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    default       => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'M5 13l4 4L19 7'],
                };
            @endphp

            <div class="bg-white border rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 {{ $isLowProgress ? 'border-orange-200' : 'border-gray-100' }}"
                 id="kegiatan-card-{{ $kegiatan->id }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 text-[15px] mb-1.5 truncate" title="{{ $kegiatan->nama }}">
                            {{ Str::limit($kegiatan->nama, 50) }}
                        </h3>
                        <div class="flex items-center gap-1.5 text-[13px] text-gray-500 mb-1">
                            <svg class="w-[14px] h-[14px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">{{ $kegiatan->lokasiLahan?->alamat ?? 'Lokasi tidak ditentukan' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[13px] text-gray-500">
                            <svg class="w-[14px] h-[14px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} text-[11px] font-bold px-3 py-1.5 rounded-full flex-shrink-0 ml-3">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $statusConfig['icon'] }}"/>
                        </svg>
                        {{ $kegiatan->status }}
                    </div>
                </div>

                {{-- Progress Bar (AC-8) --}}
                <div class="mb-5 mt-4">
                    <div class="flex justify-between items-end mb-2">
                        <div class="flex items-center gap-1.5 text-[13px] font-medium text-gray-500">
                            <svg class="w-4 h-4" style="color: {{ $progressColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Pohon Ditanam
                        </div>
                        <div class="text-[14px] font-bold text-gray-900" id="progress-text-{{ $kegiatan->id }}">
                            {{ number_format($kegiatan->realisasi_pohon, 0, ',', '.') }}
                            <span class="text-xs font-medium text-gray-400">/ {{ number_format($kegiatan->target_pohon, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="w-full rounded-full h-2.5 mb-1.5 overflow-hidden" style="background: {{ $progressBg }}">
                        <div class="h-2.5 rounded-full transition-all duration-700 ease-out" id="progress-bar-{{ $kegiatan->id }}"
                             style="width: {{ $percentage }}%; background-color: {{ $progressColor }}"></div>
                    </div>
                    <div class="text-[11px] font-medium {{ $isLowProgress ? 'text-orange-500' : 'text-gray-400' }}" id="progress-pct-{{ $kegiatan->id }}">
                        {{ $percentage }}% tercapai
                        @if($isLowProgress)
                            <span class="ml-1">⚠️ Progress rendah</span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons (AC-6) --}}
                <div class="flex gap-3">
                    <a href="{{ route('petugas.realisasi', ['kegiatan_id' => $kegiatan->id]) }}"
                            class="flex-1 bg-[#1a8245] hover:bg-green-800 text-white text-[13px] font-semibold py-2.5 rounded-xl flex items-center justify-center gap-2 transition-colors min-h-[44px]">
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Catat Realisasi
                    </a>
                    <button class="px-5 border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-[13px] font-semibold rounded-xl flex items-center justify-center gap-2 transition-colors min-h-[44px]">
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Dokumentasi
                    </button>
                </div>
            </div>

        @empty
            {{-- AC-10: Empty State --}}
            <div class="col-span-full py-16 text-center bg-white border border-gray-100 rounded-2xl shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-50 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-600 mb-1">Tidak ada kegiatan aktif saat ini</h3>
                <p class="text-sm text-gray-400 mb-4">Anda belum ditugaskan ke kegiatan yang sedang berlangsung.</p>
                <a href="{{ route('petugas.semua-kegiatan') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1a8245] text-white text-sm font-semibold rounded-xl hover:bg-green-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Lihat Semua Kegiatan
                </a>
            </div>
        @endforelse
    </div>
</div>

{{-- Modal: Catat Realisasi (AC-6) --}}
<div id="realisasiModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeRealisasiModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative animate-modal-in overflow-hidden">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-[#1a8245] to-[#15803d] p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg">Catat Realisasi</h3>
                        <p class="text-green-100 text-sm mt-0.5">Pencatatan pohon yang ditanam</p>
                    </div>
                    <button onclick="closeRealisasiModal()" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form id="realisasiForm" class="p-5">
                <input type="hidden" id="modal_kegiatan_id">

                {{-- Pre-filled: Nama Kegiatan (read-only) --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Nama Kegiatan</label>
                    <input type="text" id="modal_nama_kegiatan" readonly
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 font-medium">
                </div>

                {{-- Pre-filled: Lokasi (read-only) --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Lokasi</label>
                    <input type="text" id="modal_lokasi" readonly
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 font-medium">
                </div>

                {{-- Jenis Pohon (dropdown) --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Jenis Pohon <span class="text-red-400">*</span></label>
                    <select id="modal_jenis_pohon" required
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                        <option value="">Memuat jenis pohon...</option>
                    </select>
                    <p id="error_jenis_pohon" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                {{-- Jumlah Pohon --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Jumlah Pohon <span class="text-red-400">*</span></label>
                    <input type="number" id="modal_jumlah" min="1" max="10000" required placeholder="Masukkan jumlah pohon"
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                    <p id="error_jumlah" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                {{-- Catatan --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Catatan <span class="text-gray-300">(opsional)</span></label>
                    <textarea id="modal_catatan" rows="3" maxlength="500" placeholder="Tambahkan catatan..."
                              class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all resize-none"></textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="button" onclick="closeRealisasiModal()"
                            class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 font-semibold text-sm rounded-xl hover:bg-gray-50 transition-colors min-h-[44px]">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitRealisasi"
                            class="flex-1 px-4 py-2.5 bg-[#1a8245] text-white font-semibold text-sm rounded-xl hover:bg-green-800 transition-colors flex items-center justify-center gap-2 min-h-[44px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .animate-modal-in { animation: modalIn 0.25s ease-out; }
</style>
@endsection

@push('scripts')
<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
    let jenisPohonData = [];

    // Fetch jenis pohon on page load
    fetch('{{ route("petugas.api.jenis-pohon") }}')
        .then(r => r.json())
        .then(data => { jenisPohonData = data; })
        .catch(() => { console.error('Gagal memuat jenis pohon'); });

    function openRealisasiModal(kegiatanId, nama, lokasi) {
        document.getElementById('modal_kegiatan_id').value = kegiatanId;
        document.getElementById('modal_nama_kegiatan').value = nama;
        document.getElementById('modal_lokasi').value = lokasi;
        document.getElementById('modal_jumlah').value = '';
        document.getElementById('modal_catatan').value = '';

        // Populate jenis pohon dropdown
        const select = document.getElementById('modal_jenis_pohon');
        select.innerHTML = '<option value="">-- Pilih Jenis Pohon --</option>';
        jenisPohonData.forEach(jp => {
            const price = new Intl.NumberFormat('id-ID').format(jp.harga);
            select.innerHTML += `<option value="${jp.id}">${jp.nama} (${jp.nama_latin || '-'}) - Rp ${price}</option>`;
        });

        // Clear errors
        document.querySelectorAll('[id^="error_"]').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });
        document.getElementById('realisasiModal').classList.remove('hidden');
    }

    function closeRealisasiModal() {
        document.getElementById('realisasiModal').classList.add('hidden');
    }

    // Form submit (AC-6)
    document.getElementById('realisasiForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitRealisasi');
        const kegiatanId = document.getElementById('modal_kegiatan_id').value;

        // Clear errors
        document.querySelectorAll('[id^="error_"]').forEach(el => { el.classList.add('hidden'); });

        // Client validation
        const jenisPohonId = document.getElementById('modal_jenis_pohon').value;
        const jumlah = document.getElementById('modal_jumlah').value;
        let hasError = false;

        if (!jenisPohonId) {
            document.getElementById('error_jenis_pohon').textContent = 'Pilih jenis pohon.';
            document.getElementById('error_jenis_pohon').classList.remove('hidden');
            hasError = true;
        }
        if (!jumlah || jumlah < 1) {
            document.getElementById('error_jumlah').textContent = 'Masukkan jumlah pohon minimal 1.';
            document.getElementById('error_jumlah').classList.remove('hidden');
            hasError = true;
        }
        if (hasError) return;

        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg> Menyimpan...';

        try {
            const url = '{{ url("petugas/api/kegiatan") }}/' + kegiatanId + '/realisasi';
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({
                    jenis_pohon_id: jenisPohonId,
                    jumlah: parseInt(jumlah),
                    catatan: document.getElementById('modal_catatan').value || null,
                }),
            });

            const data = await res.json();

            if (res.ok) {
                closeRealisasiModal();
                showToast(data.message || 'Realisasi berhasil dicatat!', 'success');
                updateProgressUI(kegiatanId, data);
            } else {
                if (data.errors) {
                    Object.entries(data.errors).forEach(([key, msgs]) => {
                        const el = document.getElementById('error_' + key);
                        if (el) { el.textContent = msgs[0]; el.classList.remove('hidden'); }
                    });
                } else {
                    showToast(data.message || 'Gagal menyimpan realisasi.', 'error');
                }
            }
        } catch (err) {
            showToast('Terjadi kesalahan jaringan. Coba lagi.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan';
        }
    });

    // Update progress bar after realisasi (AC-7)
    function updateProgressUI(kegiatanId, data) {
        const textEl = document.getElementById('progress-text-' + kegiatanId);
        const barEl = document.getElementById('progress-bar-' + kegiatanId);
        const pctEl = document.getElementById('progress-pct-' + kegiatanId);

        if (textEl) {
            const fmt = n => new Intl.NumberFormat('id-ID').format(n);
            textEl.innerHTML = `${fmt(data.realisasi_pohon)} <span class="text-xs font-medium text-gray-400">/ ${fmt(data.target_pohon)}</span>`;
        }
        if (barEl) {
            barEl.style.width = data.progress + '%';
            if (data.progress < 50) barEl.style.backgroundColor = '#f97316';
            else if (data.progress <= 75) barEl.style.backgroundColor = '#eab308';
            else barEl.style.backgroundColor = '#22c55e';
        }
        if (pctEl) {
            pctEl.textContent = data.progress + '% tercapai';
            pctEl.className = data.progress < 50 ? 'text-[11px] font-medium text-orange-500' : 'text-[11px] font-medium text-gray-400';
        }
    }

    // Auto-refresh dashboard every 5 minutes (AC-7, Exception 4)
    setInterval(() => {
        fetch('{{ route("petugas.api.dashboard") }}')
            .then(r => r.json())
            .then(activities => {
                activities.forEach(a => {
                    updateProgressUI(a.id, {
                        realisasi_pohon: a.realisasi_pohon,
                        target_pohon: a.target_pohon,
                        progress: a.progress
                    });
                });
            })
            .catch(() => {});
    }, 300000);
</script>
@endpush