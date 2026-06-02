@extends('layouts.auth')

@section('title', 'Notifikasi')

@section('content')
<div class="w-full max-w-3xl px-6 mt-12 pb-16">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🔔 Notifikasi</h1>
            <p class="text-gray-500 text-sm mt-0.5">Kelola semua pemberitahuan akun Anda.</p>
        </div>
        @if($belumDibaca->count() > 0)
        <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
            @csrf
            @method('PATCH')
            <button type="submit"
                class="text-sm bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition font-medium">
                ✓ Tandai semua dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- Success message --}}
    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tab --}}
    <div class="flex gap-2 mb-6">
        <a href="{{ route('notifikasi.index', ['tab' => 'belum_dibaca']) }}"
           class="px-4 py-2 rounded-full text-sm font-semibold border transition
           {{ $tab === 'belum_dibaca' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-300 hover:border-green-500' }}">
            Belum Dibaca
            @if($belumDibaca->count() > 0)
                <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5">{{ $belumDibaca->count() }}</span>
            @endif
        </a>
        <a href="{{ route('notifikasi.index', ['tab' => 'sudah_dibaca']) }}"
           class="px-4 py-2 rounded-full text-sm font-semibold border transition
           {{ $tab === 'sudah_dibaca' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-300 hover:border-green-500' }}">
            Sudah Dibaca
        </a>
    </div>

    {{-- Tab: Belum Dibaca --}}
    @if($tab === 'belum_dibaca')
        @forelse($belumDibaca as $notif)
        <div class="bg-white border border-green-100 rounded-xl p-4 mb-3 shadow-sm flex items-start gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                        {{ $notif->tipe_label }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                <p class="font-semibold text-gray-800 text-sm">{{ $notif->judul }}</p>
                <p class="text-gray-600 text-sm mt-0.5">{{ $notif->pesan }}</p>
            </div>
            <form method="POST" action="{{ route('notifikasi.baca', $notif->id) }}" class="flex-shrink-0">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="text-xs text-green-600 border border-green-300 px-3 py-1.5 rounded-lg hover:bg-green-50 transition font-medium whitespace-nowrap">
                    Tandai dibaca
                </button>
            </form>
        </div>
        @empty
        <div class="text-center py-16 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <p class="text-3xl mb-3">✅</p>
            <p class="font-semibold text-gray-700">Tidak ada notifikasi baru</p>
            <p class="text-sm text-gray-400 mt-1">Semua notifikasi sudah dibaca.</p>
        </div>
        @endforelse
    @endif

    {{-- Tab: Sudah Dibaca --}}
    @if($tab === 'sudah_dibaca')
        @forelse($sudahDibaca as $notif)
        <div class="bg-white border border-gray-100 rounded-xl p-4 mb-3 opacity-75">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                    {{ $notif->tipe_label }}
                </span>
                <span class="text-xs text-gray-400">{{ $notif->read_at?->diffForHumans() ?? '-' }}</span>
            </div>
            <p class="font-semibold text-gray-700 text-sm">{{ $notif->judul }}</p>
            <p class="text-gray-500 text-sm mt-0.5">{{ $notif->pesan }}</p>
        </div>
        @empty
        <div class="text-center py-16 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <p class="text-3xl mb-3">📭</p>
            <p class="font-semibold text-gray-700">Belum ada notifikasi yang dibaca</p>
        </div>
        @endforelse
    @endif

</div>
@endsection
