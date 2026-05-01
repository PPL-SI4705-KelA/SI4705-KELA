@extends('layouts.auth')

@section('title', $kegiatan->nama . ' - Greennovate Admin')

@section('content')
<div class="w-full max-w-2xl px-6 mt-8 mb-16">

    <div class="mb-6">
        <a href="{{ route('admin.kegiatan.index') }}"
           class="text-sm text-gray-400 hover:text-gray-600 mb-2 inline-block">
            ← Kembali ke Kelola Kegiatan
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ $kegiatan->nama }}</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold mb-0.5">Lokasi</p>
                <p class="text-gray-800">{{ $kegiatan->lokasi }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold mb-0.5">Tanggal</p>
                <p class="text-gray-800">{{ $kegiatan->tanggal->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold mb-0.5">Target</p>
                <p class="text-gray-800">{{ number_format($kegiatan->target) }} pohon</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold mb-0.5">Kuota Peserta</p>
                <p class="text-gray-800">{{ number_format($kegiatan->kuota) }} orang</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold mb-0.5">Status</p>
                @php
                    $badge = match($kegiatan->status) {
                        'aktif'    => 'bg-green-100 text-green-700',
                        'nonaktif' => 'bg-gray-100 text-gray-600',
                        'selesai'  => 'bg-blue-100 text-blue-700',
                        default    => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                    {{ ucfirst($kegiatan->status) }}
                </span>
            </div>
        </div>

        @if($kegiatan->deskripsi)
            <div class="border-t pt-4">
                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Deskripsi</p>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $kegiatan->deskripsi }}</p>
            </div>
        @endif

        <div class="border-t pt-4 flex gap-3">
            <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                Edit Kegiatan
            </a>
            <form method="POST"
                  action="{{ route('admin.kegiatan.destroy', $kegiatan) }}"
                  onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-lg border border-red-200 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
