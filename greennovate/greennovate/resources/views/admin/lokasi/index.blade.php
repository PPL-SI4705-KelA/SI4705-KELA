@extends('layouts.admin')

@section('title', 'Lokasi Lahan – Admin Greennovate')
@section('page-title', 'Lokasi Lahan')
@section('page-subtitle', 'Master data lokasi lahan untuk kegiatan, donasi, dan pembelian pohon')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">Total: <span class="font-semibold text-gray-800">{{ $lokasis->total() }}</span> lokasi</p>
    <a href="{{ route('admin.lokasi.create') }}" id="btn-tambah-lokasi"
       class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold px-5 py-2.5 rounded-xl transition-all hover:shadow-lg text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Lokasi
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    @if($lokasis->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
            </div>
            <h3 class="text-gray-700 font-semibold mb-1">Belum ada lokasi lahan</h3>
            <p class="text-gray-400 text-sm mb-4">Tambahkan lokasi pertama untuk mulai mengelola lahan.</p>
            <a href="{{ route('admin.lokasi.create') }}"
               class="inline-flex items-center gap-2 bg-[#0D8B41] text-white font-medium px-5 py-2.5 rounded-xl text-sm hover:bg-[#085c2b] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Lokasi Sekarang
            </a>
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider w-12">#</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Nama Lokasi</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-4 text-right font-semibold text-gray-600 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($lokasis as $lokasi)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-400">{{ $loop->iteration + ($lokasis->currentPage() - 1) * $lokasis->perPage() }}</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">{{ $lokasi->nama }}</div>
                        @if($lokasi->deskripsi)
                            <div class="text-xs text-gray-400 mt-0.5 truncate max-w-[240px]">{{ $lokasi->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600 max-w-[280px]">
                        <span class="truncate block">{{ $lokasi->alamat }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $lokasi->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.lokasi.edit', $lokasi) }}"
                               id="btn-edit-{{ $lokasi->id }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.lokasi.destroy', $lokasi) }}"
                                  onsubmit="return confirm('Yakin hapus lokasi \'{{ $lokasi->nama }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" id="btn-hapus-{{ $lokasi->id }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($lokasis->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $lokasis->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
