@extends('layouts.petugas')

@section('title', 'Semua Kegiatan - Greennovate')
@section('header', 'Semua Kegiatan')

@section('content')
<div class="max-w-6xl">

    {{-- Search & Filter Bar (AC-5) --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm mb-6">
        <form method="GET" action="{{ route('petugas.semua-kegiatan') }}" id="filterForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Cari Kegiatan</label>
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama kegiatan..."
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
                               id="searchInput">
                    </div>
                </div>

                {{-- Filter Status --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Status</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none" onchange="document.getElementById('filterForm').submit()">
                        <option value="Semua" {{ request('status') == 'Semua' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="Berlangsung" {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="Persiapan" {{ request('status') == 'Persiapan' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                {{-- Filter Lokasi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Lokasi</label>
                    <select name="lokasi" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasiList as $lok)
                            <option value="{{ $lok->id }}" {{ request('lokasi') == $lok->id ? 'selected' : '' }}>{{ $lok->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-[#1a8245] text-white text-sm font-semibold rounded-xl hover:bg-green-800 transition-colors min-h-[44px]">
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'status', 'lokasi']))
                        <a href="{{ route('petugas.semua-kegiatan') }}" class="px-4 py-2.5 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors min-h-[44px] flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Results Info --}}
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-gray-500">
            Menampilkan <span class="font-semibold text-gray-700">{{ $kegiatans->firstItem() ?? 0 }}-{{ $kegiatans->lastItem() ?? 0 }}</span>
            dari <span class="font-semibold text-gray-700">{{ $kegiatans->total() }}</span> kegiatan
        </p>
    </div>

    {{-- Table (AC-4) --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Nama Kegiatan</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Lokasi</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Status</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Progress</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kegiatans as $kegiatan)
                        @php
                            $pct = $kegiatan->target_pohon > 0 ? min(round(($kegiatan->realisasi_pohon / $kegiatan->target_pohon) * 100), 100) : 0;
                            if ($pct < 50) $barColor = '#f97316';
                            elseif ($pct <= 75) $barColor = '#eab308';
                            else $barColor = '#22c55e';

                            $statusCfg = match($kegiatan->status) {
                                'Berlangsung' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                                'Persiapan'   => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                                'Selesai'     => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                                'Dibatalkan'  => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                                default       => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-900 truncate max-w-[200px]" title="{{ $kegiatan->nama }}">{{ $kegiatan->nama }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                <div class="truncate max-w-[180px]">{{ $kegiatan->lokasiLahan?->alamat ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $statusCfg['bg'] }} {{ $statusCfg['text'] }}">
                                    {{ $kegiatan->status === 'Persiapan' ? 'Akan Datang' : $kegiatan->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 min-w-[140px]">
                                    <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full transition-all" style="width: {{ $pct }}%; background-color: {{ $barColor }}"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600 w-10 text-right">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('petugas.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-[12px] font-semibold text-[#1a8245] border border-green-200 rounded-lg hover:bg-green-50 transition-colors min-h-[36px]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    @if(in_array($kegiatan->status, ['Berlangsung', 'Persiapan']))
                                        <a href="{{ route('petugas.realisasi', ['kegiatan_id' => $kegiatan->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-[12px] font-semibold text-white bg-[#1a8245] hover:bg-green-800 rounded-lg transition-colors min-h-[36px]">
                                            Catat
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="w-14 h-14 mx-auto mb-3 bg-gray-50 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500">Tidak ada kegiatan ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Coba ubah filter atau kata kunci pencarian</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination (AC-7: TC-07) --}}
    @if($kegiatans->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Halaman {{ $kegiatans->currentPage() }} dari {{ $kegiatans->lastPage() }}
            </div>
            <div class="flex items-center gap-1">
                @if($kegiatans->onFirstPage())
                    <span class="px-3 py-2 text-sm text-gray-300 cursor-not-allowed">← Sebelumnya</span>
                @else
                    <a href="{{ $kegiatans->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-[#1a8245] transition-colors">← Sebelumnya</a>
                @endif

                @foreach($kegiatans->getUrlRange(max(1, $kegiatans->currentPage()-2), min($kegiatans->lastPage(), $kegiatans->currentPage()+2)) as $page => $url)
                    @if($page == $kegiatans->currentPage())
                        <span class="px-3.5 py-2 text-sm font-bold text-white bg-[#1a8245] rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3.5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if($kegiatans->hasMorePages())
                    <a href="{{ $kegiatans->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-[#1a8245] transition-colors">Selanjutnya →</a>
                @else
                    <span class="px-3 py-2 text-sm text-gray-300 cursor-not-allowed">Selanjutnya →</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Debounced search (AC-5: 300ms debounce)
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 300);
    });
</script>
@endpush
