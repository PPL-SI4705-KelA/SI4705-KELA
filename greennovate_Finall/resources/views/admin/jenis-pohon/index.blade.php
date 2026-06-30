@extends('layouts.admin')

@section('title', 'Manajemen Jenis Pohon - Greennovate Admin')
@section('page-title', 'Manajemen Jenis Pohon')
@section('page-subtitle', 'Kelola data jenis pohon dan harga untuk kegiatan penghijauan')

@section('content')
<div class="w-full">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Jenis Pohon</h2>
            <p class="text-sm text-gray-500 mt-0.5">Total: {{ $jenisPohons->total() }} jenis pohon</p>
        </div>
        <a href="{{ route('admin.jenis-pohon.create') }}"
           id="btn-tambah-jenis-pohon"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jenis Pohon
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-4">
        <form method="GET" action="{{ route('admin.jenis-pohon.index') }}"
              class="flex flex-wrap gap-3 p-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari nama pohon</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Contoh: Mahoni..."
                       id="input-search-pohon"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                <select name="kategori"
                        id="select-filter-kategori"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            </div>
            <button type="submit"
                    id="btn-filter"
                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-900 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'kategori']))
                <a href="{{ route('admin.jenis-pohon.index') }}"
                   id="btn-reset-filter"
                   class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800 underline">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="table-jenis-pohon">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase w-12">ID</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Pohon</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Latin</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Harga</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jenisPohons as $pohon)
                    <tr class="hover:bg-gray-50 transition" id="row-pohon-{{ $pohon->id }}">
                        <td class="px-4 py-3 text-gray-400 font-mono text-xs">{{ $pohon->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $pohon->nama }}</td>
                        <td class="px-4 py-3 text-gray-500 italic">{{ $pohon->nama_latin ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                {{ $pohon->kategori->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-800 font-semibold">{{ $pohon->harga_formatted }}</td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.jenis-pohon.edit', $pohon) }}"
                                   id="btn-edit-{{ $pohon->id }}"
                                   class="text-xs font-medium text-blue-600 hover:underline">Edit</a>
                                <form method="POST"
                                      action="{{ route('admin.jenis-pohon.destroy', $pohon) }}"
                                      id="form-delete-{{ $pohon->id }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis pohon \'{{ $pohon->nama }}\'?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            id="btn-delete-{{ $pohon->id }}"
                                            class="text-xs font-medium text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Belum ada data jenis pohon.
                            <a href="{{ route('admin.jenis-pohon.create') }}" class="text-green-600 hover:underline ml-1">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jenisPohons->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $jenisPohons->links() }}</div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">← Kembali ke Dashboard</a>
    </div>
</div>
@endsection
