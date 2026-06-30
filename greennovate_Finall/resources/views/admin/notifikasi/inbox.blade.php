@extends('layouts.admin')

@section('title', 'Inbox Notifikasi – Admin Greennovate')
@section('page-title', 'Inbox Notifikasi')
@section('page-subtitle', 'Notifikasi masuk yang memerlukan tindakan admin')

@section('content')

{{-- Header Stats --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Belum Dibaca</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $belumDibaca->count() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Sudah Dibaca</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $sudahDibaca->count() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 col-span-2 md:col-span-1 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $belumDibaca->count() + $sudahDibaca->count() }}</p>
        </div>
        @if($belumDibaca->count() > 0)
            <form method="POST" action="{{ route('admin.inbox.baca-semua') }}" id="form-baca-semua">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="px-4 py-2 bg-[#0D8B41] text-white text-xs font-semibold rounded-lg hover:bg-[#085c2b] transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Alert sukses --}}
@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Tabs --}}
<div class="flex gap-1 mb-4 bg-gray-100 p-1 rounded-xl w-fit">
    <a href="{{ route('admin.inbox', ['tab' => 'belum_dibaca']) }}"
        class="px-5 py-2 rounded-lg text-sm font-semibold transition
            {{ $tab === 'belum_dibaca'
                ? 'bg-white text-gray-900 shadow-sm'
                : 'text-gray-500 hover:text-gray-700' }}">
        Belum Dibaca
        @if($belumDibaca->count() > 0)
            <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold rounded-full bg-red-500 text-white">
                {{ $belumDibaca->count() > 99 ? '99+' : $belumDibaca->count() }}
            </span>
        @endif
    </a>
    <a href="{{ route('admin.inbox', ['tab' => 'sudah_dibaca']) }}"
        class="px-5 py-2 rounded-lg text-sm font-semibold transition
            {{ $tab === 'sudah_dibaca'
                ? 'bg-white text-gray-900 shadow-sm'
                : 'text-gray-500 hover:text-gray-700' }}">
        Sudah Dibaca
    </a>
</div>

{{-- Daftar Notifikasi --}}
<div class="space-y-3" id="notif-list">

    @php
        $list = $tab === 'sudah_dibaca' ? $sudahDibaca : $belumDibaca;
    @endphp

    @forelse($list as $notif)
        <div class="bg-white rounded-xl border {{ $notif->is_read ? 'border-gray-200' : 'border-l-4 border-l-[#0D8B41] border-gray-200' }} p-5 flex items-start gap-4 transition hover:shadow-sm"
             id="notif-{{ $notif->id }}">

            {{-- Ikon Tipe --}}
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 text-lg
                {{ $notif->tipe === 'pembayaran' ? 'bg-blue-50' : ($notif->tipe === 'donasi' ? 'bg-green-50' : 'bg-gray-100') }}">
                {{ $notif->ikonTipe() }}
            </div>

            {{-- Konten --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm {{ $notif->is_read ? '' : 'text-[#0D8B41]' }}">
                            {{ $notif->judul }}
                        </p>
                        <p class="text-gray-500 text-sm mt-0.5">{{ $notif->pesan }}</p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xs text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</p>
                        <p class="text-[11px] text-gray-300 mt-0.5">{{ $notif->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-3">
                    {{-- Badge tipe --}}
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $notif->warnaTipe() }}">
                        {{ ucfirst($notif->tipe) }}
                    </span>

                    {{-- Tombol tandai dibaca (hanya untuk yang belum dibaca) --}}
                    @if(! $notif->is_read)
                        <button
                            onclick="tandaiDibaca({{ $notif->id }})"
                            class="text-xs text-[#0D8B41] font-semibold hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tandai Dibaca
                        </button>
                    @else
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Dibaca {{ $notif->read_at?->format('d M Y, H:i') }}
                        </span>
                    @endif

                    {{-- Link ke halaman pembelian jika tipe pembayaran --}}
                    @if($notif->tipe === 'pembayaran')
                        <a href="{{ route('admin.pembelian.index') }}"
                            class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1 ml-auto">
                            Lihat Pembelian
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-gray-200 py-16 flex flex-col items-center gap-3">
            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-gray-400 font-medium text-sm">
                {{ $tab === 'sudah_dibaca' ? 'Belum ada notifikasi yang sudah dibaca.' : 'Tidak ada notifikasi baru.' }}
            </p>
        </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
function tandaiDibaca(id) {
    fetch(`/admin/inbox/${id}/baca`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        // Hapus elemen notifikasi dari list dengan animasi
        const el = document.getElementById(`notif-${id}`);
        if (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateX(20px)';
            el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            setTimeout(() => el.remove(), 300);
        }

        // Update kedua badge (sidebar & topbar)
        const count = data.unread_count ?? 0;
        ['admin-notif-badge-sidebar', 'admin-notif-badge-topbar'].forEach(badgeId => {
            const badge = document.getElementById(badgeId);
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    })
    .catch(() => {
        alert('Gagal menandai notifikasi. Silakan coba lagi.');
    });
}
</script>
@endpush
