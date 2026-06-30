@extends('layouts.landing')

@section('title', 'Dashboard - Greennovate')

@section('content')
<div class="w-full max-w-4xl mx-auto px-6 pt-16 mt-12">

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-500 mb-8">Selamat datang kembali, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Total Donasi Anda --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-extrabold text-gray-900">
                        Rp {{ number_format($userStats['total_donasi'], 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">Total Donasi Anda</div>
                </div>
            </div>

            {{-- Pohon Dikontribusikan --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-extrabold text-gray-900">
                        {{ number_format($userStats['total_pohon'], 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">Pohon</span>
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">Pohon Dikontribusikan</div>
                </div>
            </div>

            {{-- Kegiatan Diikuti --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-extrabold text-gray-900">
                        {{ $userStats['total_kegiatan'] }} <span class="text-xs font-normal text-gray-400">Kegiatan</span>
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">Kegiatan Diikuti</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            {{-- Chat Card --}}
            <a href="{{ route('chat.index') }}" class="flex items-center gap-4 p-5 rounded-xl border border-gray-200 bg-white hover:bg-green-50 transition group">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900">Hubungi Admin</p>
                    <p class="text-sm text-gray-500 italic">Punya pertanyaan? Chat kami di sini.</p>
                </div>
                @if(isset($unreadChatCount) && $unreadChatCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadChatCount }}</span>
                @endif
            </a>

            {{-- Riwayat Card --}}
            <a href="{{ route('riwayat.index') }}" class="flex items-center gap-4 p-5 rounded-xl border border-gray-200 bg-white hover:bg-blue-50 transition group">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900">Riwayat Partisipasi</p>
                    <p class="text-sm text-gray-500 italic">Lihat kontribusi penanaman pohon Anda.</p>
                </div>
            </a>
        </div>

        {{-- ============================================== --}}
        {{-- PENCAPAIAN O2 (GAMIFIKASI) --}}
        {{-- ============================================== --}}
        <div class="mt-8 border-t border-gray-200 pt-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Pencapaian O2 Anda</h2>
                    <p class="text-gray-500 text-sm mt-1">Pantau total oksigen yang Anda sumbangkan ke bumi melalui donasi penanaman pohon.</p>
                </div>
                <a href="{{ route('profile.edit') }}#tab-achievements" class="text-sm font-semibold text-green-600 hover:text-green-700 hover:underline">Lihat Detail &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-white rounded-full shadow-sm flex items-center justify-center text-green-600 text-2xl flex-shrink-0">
                        💨
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-800 uppercase tracking-wide">Total O2</p>
                        <p class="text-3xl font-bold text-green-900 mt-1">{{ number_format($stat->total_o2_kg_per_bulan ?? 0, 2) }} <span class="text-sm font-medium text-green-700">kg/bulan</span></p>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-100 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-white rounded-full shadow-sm flex items-center justify-center text-blue-600 text-2xl flex-shrink-0">
                        🌲
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 uppercase tracking-wide">Total Pohon</p>
                        <p class="text-3xl font-bold text-blue-900 mt-1">{{ number_format($stat->total_pohon ?? 0, 2) }} <span class="text-sm font-medium text-blue-700">Pohon</span></p>
                    </div>
                </div>
            </div>
            
            @if($nextBadge)
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-6 mb-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-800">Menuju Badge: <span class="text-green-600">{{ $nextBadge->nama_gelar }}</span></h3>
                    <span class="text-xs font-semibold text-gray-500">{{ number_format($stat->total_o2_kg_per_bulan ?? 0, 2) }} / {{ number_format($nextBadge->threshold_o2, 2) }} kg O2</span>
                </div>
                @php
                    $percentage = min(100, (($stat->total_o2_kg_per_bulan ?? 0) / $nextBadge->threshold_o2) * 100);
                @endphp
                <div class="w-full bg-gray-100 rounded-full h-3.5 mb-2 overflow-hidden shadow-inner">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-3.5 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $percentage }}%">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-400">Butuh {{ number_format($nextBadge->threshold_o2 - ($stat->total_o2_kg_per_bulan ?? 0), 2) }} kg O2 lagi untuk mendapatkan badge ini.</p>
            </div>
            @else
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-2xl p-6 mb-6 text-center shadow-sm">
                <div class="text-4xl mb-3">🏆</div>
                <h3 class="text-lg font-bold text-yellow-800">Luar Biasa!</h3>
                <p class="text-sm text-yellow-700 mt-1">Anda telah mengumpulkan semua badge gamifikasi. Terima kasih atas kontribusi besar Anda bagi bumi!</p>
            </div>
            @endif

            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Koleksi Badge Anda</h3>
            
            @if($achievements->isEmpty())
                <div class="text-center py-8 bg-gray-50 rounded-2xl border border-gray-100 border-dashed">
                    <div class="text-4xl text-gray-300 mb-2">🌱</div>
                    <p class="text-gray-500 font-medium">Belum ada badge yang dikoleksi.</p>
                    <p class="text-xs text-gray-400 mt-1">Terus berdonasi untuk mencapai target O2 dan buka badge eksklusif!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($achievements as $ua)
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full z-0 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mb-3 shadow-sm">
                                🎖️
                            </div>
                            <h4 class="font-bold text-gray-900">{{ $ua->achievement->nama_gelar }}</h4>
                            <p class="text-[10px] font-semibold text-gray-400 mt-2 uppercase">Diraih pada {{ $ua->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Kontribusi/Langganan Card --}}
        <div class="mt-6">
            <div class="bg-gray-50 border border-gray-200/60 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#0D8B41] flex items-center justify-center text-white shadow-md shadow-green-900/10 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Kontribusi Penanaman Pohon</h3>
                        <p class="text-gray-500 text-sm mt-0.5">Ikut serta menghijaukan lahan bekas tambang dengan mengotomatisasi pemesanan bibit tanaman secara real-time.</p>
                    </div>
                </div>
                <div class="w-full md:w-auto flex-shrink-0">
                    <a href="{{ route('pembelian.index') }}" 
                    class="block w-full text-center bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold px-6 py-3 rounded-full text-sm transition-all shadow-md hover:shadow-lg hover:shadow-green-900/20">
                        Mulai Kontribusi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
        </form>
    </div>
</div>
@endsection
