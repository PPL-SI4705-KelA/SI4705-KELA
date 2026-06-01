@extends('layouts.landing')

@section('title', 'Riwayat Partisipasi - Greennovate')

@section('styles')
<style>
    .riwayat-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .riwayat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .badge-yellow { background: #fef3c7; color: #92400e; }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-red { background: #fee2e2; color: #991b1b; }
    .badge-gray { background: #f3f4f6; color: #374151; }
    .badge-blue { background: #dbeafe; color: #1e40af; }
    .badge-emerald { background: #d1fae5; color: #064e3b; }
    .badge-rose { background: #ffe4e6; color: #9f1239; }
    .filter-btn { padding: 6px 16px; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; border: 1.5px solid #e5e7eb; background: #fff; color: #6b7280; transition: all 0.15s; }
    .filter-btn:hover { border-color: #0D8B41; color: #0D8B41; }
    .filter-btn.active { background: #0D8B41; color: #fff; border-color: #0D8B41; }

    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .modal-overlay.show { display: flex; }
    .modal-content { background: #fff; border-radius: 16px; max-width: 540px; width: 90%; max-height: 85vh; overflow-y: auto; box-shadow: 0 25px 60px rgba(0,0,0,0.15); animation: modalIn 0.3s ease; }
    @keyframes modalIn { from { opacity:0; transform: translateY(20px) scale(0.97); } to { opacity:1; transform: translateY(0) scale(1); } }
    .modal-content::-webkit-scrollbar { width: 4px; }
    .modal-content::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 9999px; }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state svg { margin: 0 auto 16px; }

    .qr-container { background: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; padding: 24px; text-align: center; }
    .qr-container img { max-width: 180px; margin: 0 auto; }
</style>
@endsection

@section('content')
<div class="pt-24 pb-16 max-w-5xl mx-auto px-6">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Partisipasi</h1>
        <p class="text-gray-500">Pantau seluruh kontribusi Anda: donasi, pembelian, dan kegiatan yang diikuti.</p>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('riwayat.index') }}" class="filter-btn {{ !$filterTipe ? 'active' : '' }}">Semua</a>
        <a href="{{ route('riwayat.index', ['tipe' => 'donasi']) }}" class="filter-btn {{ $filterTipe === 'donasi' ? 'active' : '' }}">💝 Donasi</a>
        <a href="{{ route('riwayat.index', ['tipe' => 'pembelian']) }}" class="filter-btn {{ $filterTipe === 'pembelian' ? 'active' : '' }}">🛒 Pembelian</a>
        <a href="{{ route('riwayat.index', ['tipe' => 'kegiatan']) }}" class="filter-btn {{ $filterTipe === 'kegiatan' ? 'active' : '' }}">🌱 Kegiatan</a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Riwayat</p>
            <p class="text-2xl font-bold text-gray-900">{{ $riwayatItems->total() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Filter Aktif</p>
            <p class="text-2xl font-bold text-[#0D8B41]">{{ $filterTipe ? ucfirst($filterTipe) : 'Semua' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Halaman</p>
            <p class="text-2xl font-bold text-gray-900">{{ $riwayatItems->currentPage() }} / {{ $riwayatItems->lastPage() }}</p>
        </div>
    </div>

    {{-- Riwayat List --}}
    @if($riwayatItems->count() > 0)
        <div class="space-y-3" id="riwayat-list">
            @foreach($riwayatItems as $item)
                <div class="riwayat-card bg-white rounded-xl border border-gray-100 p-5 shadow-sm flex items-center justify-between gap-4"
                     onclick="openDetail('{{ $item['tipe'] }}', {{ $item['id'] }})"
                     id="riwayat-item-{{ $item['tipe'] }}-{{ $item['id'] }}">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                            {{ $item['tipe'] === 'donasi' ? 'bg-rose-100' : ($item['tipe'] === 'pembelian' ? 'bg-blue-100' : 'bg-green-100') }}">
                            @if($item['tipe'] === 'donasi')
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            @elseif($item['tipe'] === 'pembelian')
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-900 truncate">{{ $item['nama'] }}</p>
                            <div class="flex items-center gap-3 text-xs text-gray-400 mt-1">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold badge-{{ $item['tipe_color'] }}">{{ $item['tipe_label'] }}</span>
                                <span>{{ $item['tanggal']->translatedFormat('d M Y') }}</span>
                                <span class="hidden sm:inline text-gray-300">•</span>
                                <span class="hidden sm:inline">{{ $item['kode'] }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- Status + Detail --}}
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="text-xs font-bold text-gray-500 hidden sm:block">{{ $item['detail'] }}</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold badge-{{ $item['status_color'] }}">
                            {{ $item['status_label'] }}
                        </span>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $riwayatItems->links() }}
        </div>
    @else
        <div class="empty-state bg-white rounded-xl border border-gray-100 shadow-sm">
            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Riwayat</h3>
            <p class="text-sm text-gray-400">Mulai berkontribusi dengan mengikuti kegiatan, berdonasi, atau berbelanja.</p>
            <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-[#0D8B41] text-white text-sm font-semibold rounded-full hover:bg-[#085c2b] transition">
                Jelajahi Kegiatan
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    @endif
</div>

{{-- Detail Modal --}}
<div class="modal-overlay" id="detailModal" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div id="modalBody">
            <div class="flex items-center justify-center py-16">
                <div class="w-8 h-8 border-3 border-gray-200 border-t-[#0D8B41] rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openDetail(tipe, id) {
    const modal = document.getElementById('detailModal');
    const body = document.getElementById('modalBody');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    body.innerHTML = '<div class="flex items-center justify-center py-16"><div style="width:32px;height:32px;border:3px solid #e5e7eb;border-top-color:#0D8B41;border-radius:50%;animation:spin 0.6s linear infinite"></div></div>';

    fetch(`/riwayat/${tipe}/${id}/detail`)
        .then(r => { if(!r.ok) throw new Error('Not found'); return r.json(); })
        .then(data => { body.innerHTML = renderDetail(data); })
        .catch(() => { body.innerHTML = renderError(); });
}

function closeModal(e) {
    if (e && e.target !== e.currentTarget) return;
    document.getElementById('detailModal').classList.remove('show');
    document.body.style.overflow = '';
}

function renderDetail(d) {
    const colorMap = { yellow:'#fef3c7;color:#92400e', green:'#d1fae5;color:#065f46', red:'#fee2e2;color:#991b1b', gray:'#f3f4f6;color:#374151', blue:'#dbeafe;color:#1e40af', emerald:'#d1fae5;color:#064e3b' };
    const badgeStyle = colorMap[d.status_color] || colorMap.gray;
    const iconMap = { donasi: '💝', pembelian: '🛒', kegiatan: '🌱' };

    let html = `
    <div style="padding:24px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
            <div style="display:flex;align-items:center;gap:10px">
                <span style="font-size:1.5rem">${iconMap[d.tipe]||'📋'}</span>
                <div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:#111827;margin:0">Detail ${d.tipe_label}</h3>
                    <p style="font-size:0.75rem;color:#9ca3af;margin:0">${d.kode}</p>
                </div>
            </div>
            <button onclick="closeModal({target:document.getElementById('detailModal'),currentTarget:document.getElementById('detailModal')})" style="width:32px;height:32px;border-radius:8px;border:none;background:#f3f4f6;cursor:pointer;display:flex;align-items:center;justify-content:center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path stroke-linecap="round" d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
            <span style="display:inline-block;padding:4px 12px;border-radius:9999px;font-size:0.75rem;font-weight:700;background:${badgeStyle}">${d.status_label}</span>
        </div>

        <div style="background:#f9fafb;border-radius:12px;padding:16px;margin-bottom:16px">
            <table style="width:100%;font-size:0.85rem;border-collapse:collapse">
                <tr><td style="padding:6px 0;color:#6b7280;width:40%">Nama</td><td style="padding:6px 0;font-weight:600;color:#111827">${d.nama}</td></tr>
                <tr><td style="padding:6px 0;color:#6b7280">Tanggal</td><td style="padding:6px 0;font-weight:600;color:#111827">${d.tanggal}</td></tr>`;

    if (d.jumlah) html += `<tr><td style="padding:6px 0;color:#6b7280">Jumlah</td><td style="padding:6px 0;font-weight:600;color:#0D8B41">${d.jumlah}</td></tr>`;
    if (d.metode) html += `<tr><td style="padding:6px 0;color:#6b7280">Metode</td><td style="padding:6px 0;color:#111827">${d.metode}</td></tr>`;
    if (d.tanggal_kegiatan) html += `<tr><td style="padding:6px 0;color:#6b7280">Tanggal Kegiatan</td><td style="padding:6px 0;color:#111827">${d.tanggal_kegiatan}</td></tr>`;
    if (d.lokasi) html += `<tr><td style="padding:6px 0;color:#6b7280">Lokasi</td><td style="padding:6px 0;color:#111827">${d.lokasi}</td></tr>`;
    if (d.nama_lengkap) html += `<tr><td style="padding:6px 0;color:#6b7280">Nama Peserta</td><td style="padding:6px 0;color:#111827">${d.nama_lengkap}</td></tr>`;
    if (d.catatan) html += `<tr><td style="padding:6px 0;color:#6b7280">Catatan</td><td style="padding:6px 0;color:#111827">${d.catatan}</td></tr>`;

    html += `</table></div>`;

    // QR Code
    if (d.has_qr && d.qr_url) {
        html += `<div style="background:#f9fafb;border:2px dashed #e5e7eb;border-radius:12px;padding:20px;text-align:center;margin-bottom:16px">
            <p style="font-size:0.75rem;font-weight:600;color:#6b7280;margin-bottom:10px">QR Code</p>
            <img src="${d.qr_url}" alt="QR Code" style="max-width:160px;margin:0 auto;border-radius:8px">
        </div>`;
    }

    // Status Menunggu Konfirmasi info
    if (d.status_label === 'Menunggu Konfirmasi') {
        html += `<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:14px;margin-bottom:16px;display:flex;align-items:start;gap:10px">
            <svg style="width:20px;height:20px;color:#1e40af;flex-shrink:0;margin-top:1px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p style="font-size:0.8rem;font-weight:600;color:#1e3a8a;margin:0 0 2px">Sedang Diverifikasi</p><p style="font-size:0.75rem;color:#1e40af;margin:0">Bukti pembayaran Anda sedang diverifikasi oleh admin. Silakan tunggu.</p></div>
        </div>`;
    }

    if (d.status_label === 'Ditolak' && (d.tipe === 'donasi' || d.tipe === 'pembelian')) {
        html += `<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px;margin-bottom:16px;display:flex;align-items:start;gap:10px">
            <svg style="width:20px;height:20px;color:#dc2626;flex-shrink:0;margin-top:1px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <div><p style="font-size:0.8rem;font-weight:600;color:#991b1b;margin:0 0 2px">Bukti Ditolak</p><p style="font-size:0.75rem;color:#b91c1c;margin:0">Admin menolak bukti transfer Anda. Silakan unggah ulang bukti yang valid.</p>
            <a href="/pembayaran/${d.tipe}/${d.id}" style="display:inline-block;margin-top:8px;padding:6px 12px;background:#dc2626;color:#fff;border-radius:6px;font-size:0.75rem;font-weight:600;text-decoration:none">Unggah Ulang Bukti</a></div>
        </div>`;
    } else if (d.status_label === 'Menunggu' && (d.tipe === 'donasi' || d.tipe === 'pembelian')) {
        html += `<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px;margin-bottom:16px;display:flex;align-items:start;gap:10px">
            <svg style="width:20px;height:20px;color:#d97706;flex-shrink:0;margin-top:1px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <div><p style="font-size:0.8rem;font-weight:600;color:#92400e;margin:0 0 2px">Menunggu Pembayaran</p><p style="font-size:0.75rem;color:#b45309;margin:0">Silakan lakukan pembayaran dan unggah bukti transfer.</p>
            <a href="/pembayaran/${d.tipe}/${d.id}" style="display:inline-block;margin-top:8px;padding:6px 12px;background:#d97706;color:#fff;border-radius:6px;font-size:0.75rem;font-weight:600;text-decoration:none">Unggah Bukti Transfer</a></div>
        </div>`;
    } else if (d.status_label === 'Menunggu' || d.status_label === 'Terdaftar') {
        html += `<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px;margin-bottom:16px;display:flex;align-items:start;gap:10px">
            <svg style="width:20px;height:20px;color:#d97706;flex-shrink:0;margin-top:1px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <div><p style="font-size:0.8rem;font-weight:600;color:#92400e;margin:0 0 2px">Menunggu Proses</p><p style="font-size:0.75rem;color:#b45309;margin:0">Bukti dokumentasi atau hasil kegiatan belum diunggah oleh admin. Silakan tunggu update selanjutnya.</p></div>
        </div>`;
    }

    // Download buttons
    let hasButtons = false;
    let btnHtml = '<div style="display:flex;flex-wrap:wrap;gap:8px">';
    if (d.has_dokumentasi && d.dokumentasi_url) {
        hasButtons = true;
        btnHtml += `<a href="${d.dokumentasi_url}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#0D8B41;color:#fff;border-radius:10px;font-size:0.8rem;font-weight:600;text-decoration:none;transition:all 0.15s" onmouseover="this.style.background='#085c2b'" onmouseout="this.style.background='#0D8B41'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Unduh Dokumentasi</a>`;
    }
    if (d.has_sertifikat && d.sertifikat_url) {
        hasButtons = true;
        btnHtml += `<a href="${d.sertifikat_url}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#1e40af;color:#fff;border-radius:10px;font-size:0.8rem;font-weight:600;text-decoration:none" onmouseover="this.style.background='#1e3a8a'" onmouseout="this.style.background='#1e40af'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            Unduh Sertifikat</a>`;
    }
    btnHtml += '</div>';
    if (hasButtons) html += btnHtml;

    // File missing warning
    if (!d.has_dokumentasi && (d.status_label === 'Sukses' || d.status_label === 'Selesai' || d.status_label === 'Hadir')) {
        html += `<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px;margin-top:12px;display:flex;align-items:start;gap:10px">
            <svg style="width:20px;height:20px;color:#dc2626;flex-shrink:0;margin-top:1px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p style="font-size:0.8rem;font-weight:600;color:#991b1b;margin:0 0 2px">Dokumentasi Belum Tersedia</p><p style="font-size:0.75rem;color:#b91c1c;margin:0">File dokumentasi belum diunggah oleh admin. Hubungi admin untuk informasi lebih lanjut.</p></div>
        </div>`;
    }

    html += '</div>';
    return html;
}

function renderError() {
    return `<div style="padding:40px;text-align:center">
        <svg style="width:48px;height:48px;color:#d1d5db;margin:0 auto 12px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p style="font-weight:600;color:#6b7280;margin-bottom:4px">Data Tidak Ditemukan</p>
        <p style="font-size:0.8rem;color:#9ca3af">Detail riwayat tidak dapat dimuat.</p>
        <button onclick="closeModal({target:document.getElementById('detailModal'),currentTarget:document.getElementById('detailModal')})" style="margin-top:12px;padding:8px 20px;background:#f3f4f6;border:none;border-radius:8px;font-weight:600;font-size:0.85rem;cursor:pointer">Tutup</button>
    </div>`;
}

// Close with Escape key
document.addEventListener('keydown', e => { if(e.key === 'Escape') { document.getElementById('detailModal').classList.remove('show'); document.body.style.overflow = ''; }});
</script>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>
@endsection
