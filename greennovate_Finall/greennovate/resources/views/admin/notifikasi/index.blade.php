@extends('layouts.admin')

@section('title', 'Log Notifikasi – Admin Greennovate')
@section('page-title', 'Log Notifikasi')
@section('page-subtitle', 'Semua notifikasi yang dikirimkan ke pengguna')

@section('content')

{{-- Stats Summary --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Notifikasi</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalSemua) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Belum Dibaca</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($totalBelum) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Sudah Dibaca</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalSemua - $totalBelum) }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <form method="GET" action="{{ route('admin.notifikasi.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Tipe</label>
            <select name="tipe" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-300">
                <option value="">Semua Tipe</option>
                <option value="donasi"     {{ $tipe === 'donasi'     ? 'selected' : '' }}>Donasi</option>
                <option value="pembayaran" {{ $tipe === 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                <option value="kegiatan"   {{ $tipe === 'kegiatan'   ? 'selected' : '' }}>Kegiatan</option>
                <option value="sistem"     {{ $tipe === 'sistem'     ? 'selected' : '' }}>Sistem</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
            <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-300">
                <option value="">Semua Status</option>
                <option value="belum" {{ $status === 'belum' ? 'selected' : '' }}>Belum Dibaca</option>
                <option value="sudah" {{ $status === 'sudah' ? 'selected' : '' }}>Sudah Dibaca</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Pengguna</label>
            <select name="user_id" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-300">
                <option value="">Semua Pengguna</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="px-4 py-2 bg-[#0D8B41] text-white text-sm font-semibold rounded-lg hover:bg-[#085c2b] transition">
            Terapkan Filter
        </button>
        <a href="{{ route('admin.notifikasi.index') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition">
            Reset
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Pengguna</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Notifikasi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipe</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibuat</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibaca Pada</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($notifikasis as $notif)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-[#0D8B41] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($notif->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-xs">{{ $notif->user->name ?? '–' }}</p>
                                    <p class="text-gray-400 text-[11px]">{{ $notif->user->email ?? $notif->user->phone ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-gray-800">{{ $notif->judul }}</p>
                            <p class="text-gray-400 text-xs mt-0.5 line-clamp-1">{{ $notif->pesan }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $notif->warnaTipe() }}">
                                {{ $notif->ikonTipe() }} {{ ucfirst($notif->tipe) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($notif->is_read)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 px-2 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Dibaca
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Belum dibaca
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-400 text-xs">
                            {{ $notif->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-5 py-3.5 text-gray-400 text-xs">
                            {{ $notif->read_at ? $notif->read_at->format('d M Y, H:i') : '–' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-gray-400 font-medium">Tidak ada notifikasi ditemukan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($notifikasis->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $notifikasis->links() }}
        </div>
    @endif
</div>

@endsection
