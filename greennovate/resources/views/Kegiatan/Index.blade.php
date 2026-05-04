@extends('layouts.landing')

@section('title', 'Daftar Kegiatan - Greennovate')

@section('content')
<div class="w-full max-w-6xl mx-auto px-6 mt-4 pb-16">

    {{-- Header --}}
    <div class="mb-10 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold tracking-widest uppercase bg-green-100 text-green-700 mb-4">
            🌿 Program Lingkungan
        </span>
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Daftar Kegiatan</h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto">
            Temukan kegiatan penghijauan dan lingkungan yang sesuai dengan minat Anda.
            Bergabunglah dan berkontribusi untuk bumi yang lebih hijau.
        </p>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" action="{{ route('kegiatan.index') }}"
          class="bg-white border rounded-2xl shadow-sm p-5 mb-8">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <select name="lokasi" class="input">
                <option value="">Semua Lokasi</option>
                @foreach($lokasiList as $lokasi)
                    <option value="{{ $lokasi->id }}" {{ request('lokasi') == $lokasi->id ? 'selected' : '' }}>
                        {{ $lokasi->nama }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="input">
                <option value="">Semua Status</option>
                @foreach(['Persiapan','Berlangsung','Selesai','Dibatalkan'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ $s }}
                    </option>
                @endforeach
            </select>

            <select name="bulan" class="input">
                <option value="">Semua Bulan</option>
                @foreach(range(1,12) as $i)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <button class="bg-green-600 text-white rounded-lg px-4">Cari</button>
        </div>
    </form>

    {{-- EMPTY --}}
    @if($kegiatan->isEmpty())
        <div class="text-center py-20 text-gray-400">
            Belum ada kegiatan
        </div>
    @else

    {{-- GRID --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($kegiatan as $item)
        <a href="{{ route('kegiatan.show', $item->id) }}"
           class="bg-white rounded-xl shadow hover:shadow-lg transition">

            {{-- IMAGE --}}
            <div class="h-40 bg-green-200 relative">
                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}"
                         class="w-full h-full object-cover">
                @endif
            </div>

            <div class="p-4">
                <h3 class="font-semibold mb-2">{{ $item->nama }}</h3>

                {{-- Lokasi --}}
                <p class="text-sm text-gray-500">
                    📍 {{ $item->lokasiLahan?->nama ?? '-' }}
                </p>

                {{-- Tanggal --}}
                <p class="text-sm text-gray-500">
                    📅 {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                </p>

                {{-- Target --}}
                <p class="text-sm text-gray-500 mb-2">
                    🌱 {{ $item->target_pohon }} pohon
                </p>

                {{-- Progress realisasi --}}
                @if($item->target_pohon > 0)
                    @php
                        $persen = min(100, ($item->realisasi_pohon / $item->target_pohon) * 100);
                    @endphp
                    <div class="w-full bg-gray-200 h-2 rounded">
                        <div class="bg-green-500 h-2 rounded"
                             style="width: {{ $persen }}%"></div>
                    </div>
                @endif

                {{-- Kuota --}}
                @if($item->quota > 0)
                    <p class="text-xs text-gray-400 mt-2">
                        {{ $item->registered_count }}/{{ $item->quota }} peserta
                    </p>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    <div class="mt-10">
        {{ $kegiatan->links() }}
    </div>

    @endif

</div>
@endsection