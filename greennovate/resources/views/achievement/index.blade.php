@extends('layouts.auth')

@section('title', 'Achievement & O2 Stats')

@section('content')
<div class="w-full max-w-4xl px-6 mt-12 pb-16">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">🏆 Achievement & O2 Stats</h1>
        <p class="text-gray-500">Pantau dampak nyata kontribusimu untuk bumi.</p>
    </div>

    {{-- Stats Card --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-green-100 text-sm font-medium mb-1">Total O2 Dihasilkan</p>
            <p class="text-4xl font-bold">{{ number_format($stats->total_o2_kg_per_bulan, 1) }}</p>
            <p class="text-green-100 text-sm mt-1">kg O2 / bulan</p>
        </div>
        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-teal-100 text-sm font-medium mb-1">Pohon Berkontribusi</p>
            <p class="text-4xl font-bold">{{ number_format($stats->total_pohon, 2) }}</p>
            <p class="text-teal-100 text-sm mt-1">pohon</p>
        </div>
    </div>

    {{-- Progress Badge Berikutnya --}}
    @if($badgeBerikutnya)
    @php
        $totalO2 = $stats->total_o2_kg_per_bulan;
        $threshold = $badgeBerikutnya->threshold_o2;
        $persen = $threshold > 0 ? min(100, round(($totalO2 / $threshold) * 100)) : 0;
        $sisa = max(0, round($threshold - $totalO2, 2));
    @endphp
    <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-8 shadow-sm">
        <p class="text-sm text-gray-500 mb-2 font-medium">🎯 Menuju Badge Berikutnya</p>
        <div class="flex items-center gap-4 mb-3">
            <span class="text-4xl">{{ $badgeBerikutnya->badge_icon }}</span>
            <div>
                <p class="font-bold text-lg text-gray-800">{{ $badgeBerikutnya->nama }}</p>
                <p class="text-sm text-gray-500">Butuh <span class="text-green-600 font-semibold">{{ $sisa }} kg O2</span> lagi</p>
            </div>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3">
            <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-3 rounded-full transition-all duration-700"
                 style="width: {{ $persen }}%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-1 text-right">{{ $persen }}% ({{ number_format($totalO2,1) }} / {{ number_format($threshold,1) }} kg)</p>
    </div>
    @else
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-2xl p-6 mb-8 text-center">
        <p class="text-2xl mb-2">🌍</p>
        <p class="font-bold text-gray-800">Kamu telah meraih semua badge!</p>
        <p class="text-sm text-gray-500 mt-1">Luar biasa! Namamu terukir dalam sejarah penghijauan kampus.</p>
    </div>
    @endif

    {{-- Semua Badge --}}
    <h2 class="text-xl font-bold text-gray-800 mb-4">Koleksi Badge</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        @foreach($allAchievements as $ach)
        @php $unlocked = in_array($ach->id, $unlockedIds); @endphp
        <div class="rounded-2xl border p-5 flex items-start gap-4 transition-all
            {{ $unlocked ? 'bg-white border-green-200 shadow-sm' : 'bg-gray-50 border-gray-200 opacity-60' }}">
            <span class="text-4xl">{{ $ach->badge_icon }}</span>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-gray-800">{{ $ach->nama }}</p>
                    @if($unlocked)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">✓ Diraih</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Threshold: {{ number_format($ach->threshold_o2, 1) }} kg O2/bulan</p>
                @if($unlocked)
                    <p class="text-sm text-gray-600 mt-1">{{ $ach->pesan_dampak }}</p>
                    @php
                        $ua = $userAchievements->where('achievement_id', $ach->id)->first();
                    @endphp
                    @if($ua)
                        <p class="text-xs text-gray-400 mt-1">Diraih: {{ $ua->diraih_pada->format('d M Y') }}</p>
                    @endif
                @else
                    <p class="text-sm text-gray-400 mt-1">Selesaikan {{ number_format($ach->threshold_o2, 1) }} kg O2 untuk membuka.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($stats->total_o2_kg_per_bulan == 0)
    <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
        <p class="text-3xl mb-3">🌱</p>
        <p class="font-semibold text-gray-700">Belum ada kontribusi O2</p>
        <p class="text-sm text-gray-500 mt-1">Donasi ke kegiatan tanam atau beli pohon untuk memulai!</p>
        <a href="{{ route('kegiatan.index') }}"
           class="inline-block mt-4 bg-green-600 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-green-700 transition">
            Lihat Kegiatan
        </a>
    </div>
    @endif

</div>
@endsection
