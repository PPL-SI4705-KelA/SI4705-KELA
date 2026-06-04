@extends('layouts.admin')

@section('title', 'Daftar Donasi – Greennovate')
@section('page-title', 'Daftar Donasi')
@section('page-subtitle', 'Monitoring donasi masuk beserta status pembayarannya')

@section('content')
<div class="flex flex-col gap-6">

    {{-- Filter & Action Card --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
        {{-- Export Button (Kiri di Desktop, Bawah di Mobile) --}}
        <div class="w-full sm:w-auto order-2 sm:order-1" style="flex-grow: 1;">
            <a href="{{ route('admin.reports.donasi.csv', request()->all()) }}"
               class="w-full h-12 inline-flex items-center justify-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-bold px-5 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export ke CSV
            </a>
        </div>

        {{-- Filter Status (Kanan di Desktop, Atas di Mobile) --}}
        <form method="GET" action="{{ route('admin.donasi.index') }}" class="w-full sm:w-auto order-1 sm:order-2" id="filter-form" style="width: 100%; max-width: 176px;">
            {{-- Filter Status --}}
            <div class="w-full">
                <select id="status-filter" name="status" onchange="this.form.submit()"
                        class="w-full h-12 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100">
                    <option value="">Status Donasi</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="success" {{ request('status') == 'success' || request('status') == 'sukses' ? 'selected' : '' }}>Berhasil</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Daftar Donasi --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-sm">Daftar Transaksi Donasi</h2>
            <span class="text-xs text-gray-400">Total: {{ $donasis->total() }}</span>
        </div>

        @if($donasis->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">Belum ada data donasi</p>
            </div>
        @else
            <form action="{{ route('admin.donasi.terima-massal') }}" method="POST" id="bulk-accept-form" onsubmit="return confirm('Yakin ingin menerima donasi yang dipilih?');">
                @csrf
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex justify-end hidden" id="bulk-action-container">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-sm">
                        Terima Terpilih (<span id="bulk-count">0</span>/5)
                    </button>
                </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 w-10">Pilih</th>
                            <th class="px-6 py-4">ID Donasi</th>
                            <th class="px-6 py-4">Nama Pengguna</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4">Tanggal Donasi</th>
                            <th class="px-6 py-4">Bukti</th>
                            <th class="px-6 py-4">Status Pembayaran</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach($donasis as $d)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-center">
                                @if(strtolower($d->status) === 'pending' || strtolower($d->status) === 'menunggu_verifikasi')
                                    <input type="checkbox" name="ids[]" value="{{ $d->id }}" class="bulk-checkbox rounded text-green-600 focus:ring-green-500 w-4 h-4 border-gray-300" onchange="handleBulkCheck()">
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-700">#{{ $d->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $d->user ? $d->user->name : 'N/A' }}</div>
                                <div class="text-xs text-gray-400">{{ $d->user ? $d->user->email : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-bold">
                                Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $d->created_at ? $d->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($d->bukti_dokumentasi)
                                    <a href="{{ asset('storage/' . $d->bukti_dokumentasi) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-[#0D8B41] bg-green-50 rounded-lg hover:bg-green-100 transition-colors border border-green-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusLabel = match (strtolower($d->status)) {
                                        'pending' => 'Menunggu',
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                        'sukses'  => 'Berhasil',
                                        'expired' => 'Kadaluarsa',
                                        'gagal'   => 'Gagal',
                                        default   => $d->status,
                                    };
                                    $statusColor = match (strtolower($d->status)) {
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'menunggu_verifikasi' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'sukses'  => 'bg-green-50 text-green-700 border-green-100',
                                        'expired' => 'bg-gray-50 text-gray-700 border-gray-100',
                                        'gagal'   => 'bg-red-50 text-red-700 border-red-100',
                                        default   => 'bg-gray-50 text-gray-500 border-gray-100',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if(strtolower($d->status) === 'pending' || strtolower($d->status) === 'menunggu_verifikasi')
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.donasi.terima', $d->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menerima donasi ini?');">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow-sm">
                                                Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.donasi.tolak', $d->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak donasi ini?');">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </form>

            {{-- Pagination --}}
            @if($donasis->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $donasis->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

<script>
function handleBulkCheck() {
    const checkboxes = document.querySelectorAll('.bulk-checkbox:checked');
    const allCheckboxes = document.querySelectorAll('.bulk-checkbox');
    const bulkContainer = document.getElementById('bulk-action-container');
    const countSpan = document.getElementById('bulk-count');
    
    countSpan.textContent = checkboxes.length;

    if (checkboxes.length > 0) {
        bulkContainer.classList.remove('hidden');
    } else {
        bulkContainer.classList.add('hidden');
    }

    if (checkboxes.length >= 5) {
        allCheckboxes.forEach(cb => {
            if (!cb.checked) cb.disabled = true;
        });
    } else {
        allCheckboxes.forEach(cb => {
            cb.disabled = false;
        });
    }
}
</script>
@endsection
