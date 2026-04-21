@extends('layouts.auth')

@section('title', 'Beranda - Greennovate')

@section('content')
<div class="w-full max-w-5xl px-6 flex flex-col lg:flex-row items-center justify-between gap-12 pb-24 mt-8 lg:mt-12">
    <!-- Left Column: Copy & CTA -->
    <div class="lg:w-1/2 flex flex-col gap-6" style="animation: fade-in-up 0.8s ease-out;">
        
        <div class="inline-flex items-center gap-2 bg-green-50 rounded-full px-4 py-1.5 text-sm font-medium text-green-700 w-max border border-green-100">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            Platform Aksi Lingkungan #1
        </div>

        <h1 class="text-5xl lg:text-6xl font-black text-[#1b1b18] leading-[1.1] tracking-tight">
            Bersama <span class="text-[#0D8B41]">Menghijaukan</span> Masa Depan Bumi
        </h1>
        <p class="text-lg text-gray-500 leading-relaxed font-medium mt-2">
            Greennovate adalah platform kolaborasi yang menghubungkan relawan, donatur, dan komunitas peduli lingkungan untuk menciptakan dampak nyata bagi kelestarian alam secara berkelanjutan.
        </p>
        
        <!-- CTA -->
        <div class="flex flex-wrap items-center gap-4 mt-6">
            <a href="{{ route('register') }}" class="bg-[#0D8B41] hover:bg-[#0a6630] text-white px-8 py-3.5 rounded-full font-semibold transition-all transform hover:scale-105 hover:-translate-y-0.5 shadow-lg hover:shadow-green-900/30 flex items-center gap-2">
                Daftar Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
            <a href="{{ route('login') }}" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-8 py-3.5 rounded-full font-semibold transition-all transform hover:scale-105 hover:-translate-y-0.5 shadow-sm hover:border-[#0D8B41] hover:text-[#0D8B41]">
                Masuk
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-6 mt-10 pt-8 border-t border-gray-100">
            <div class="flex flex-col group">
                <span class="text-3xl font-black text-[#1b1b18] group-hover:text-[#0D8B41] transition-colors">2k+</span>
                <span class="text-sm text-gray-500 font-medium">Relawan Aktif</span>
            </div>
            <div class="flex flex-col group">
                <span class="text-3xl font-black text-[#1b1b18] group-hover:text-[#0D8B41] transition-colors">50+</span>
                <span class="text-sm text-gray-500 font-medium">Komunitas</span>
            </div>
            <div class="flex flex-col group">
                <span class="text-3xl font-black text-[#1b1b18] group-hover:text-[#0D8B41] transition-colors">15k</span>
                <span class="text-sm text-gray-500 font-medium">Pohon Ditanam</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Image Hero -->
    <div class="lg:w-1/2 w-full mt-10 lg:mt-0 relative group" style="animation: fade-in-left 1s ease-out;">
        <!-- Backdrop blur / shadow effect -->
        <div class="absolute -inset-3 bg-gradient-to-tr from-[#0D8B41]/30 to-teal-400/20 rounded-[2.5rem] blur-2xl opacity-60 group-hover:opacity-100 transition duration-700"></div>
        
        <!-- Main Image -->
        <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/50 bg-white">
            <img 
                src="{{ asset('images/hero.png') }}" 
                alt="Relawan Greennovate menanam pohon di lingkungan" 
                class="w-full h-auto object-cover transform hover:scale-105 transition duration-[1.5s] ease-out lg:aspect-[4/5] aspect-[4/3] object-center"
                onerror="this.onerror=null; this.src='{{ asset('images/fallback_placeholder.png') }}';"
            >
            <!-- Badge overlay top-right -->
            <div class="absolute top-6 right-6 bg-white/95 backdrop-blur px-4 py-2 rounded-xl shadow-lg border border-gray-100 flex items-center gap-2 transform hover:scale-105 transition duration-300">
                <span class="flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-[#0D8B41]"></span>
                </span>
                <span class="text-sm font-bold text-gray-800">Live Campaign</span>
            </div>

            <!-- Badge overlay bottom-left -->
            <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-md px-5 py-3.5 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/50 flex items-center gap-4 transform hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-[#0D8B41]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest mb-0.5">Dampak Nyata</p>
                    <p class="text-base font-black text-gray-900 leading-none">15,000+ Pohon</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in-left {
        0% { opacity: 0; transform: translateX(30px); }
        100% { opacity: 1; transform: translateX(0); }
    }
</style>
@endsection
