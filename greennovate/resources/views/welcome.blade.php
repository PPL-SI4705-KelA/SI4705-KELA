@extends('layouts.landing')

@section('title', 'Greennovate – Bersama Menghijaukan Bumi')

@section('content')

{{-- ========== HERO ========== --}}
<section class="relative min-h-screen flex items-center overflow-hidden pt-20">
    <div class="orb w-[700px] h-[700px] bg-green-200/50 top-[-200px] right-[-200px]"></div>
    <div class="orb w-[400px] h-[400px] bg-emerald-100/60 bottom-[-100px] left-[-150px]"></div>

    <div class="max-w-6xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center relative z-10">
        {{-- Copy --}}
        <div class="hero-copy">
            <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 rounded-full px-4 py-1.5 mb-6">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping-slow absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </span>
                <span class="text-sm font-semibold text-green-700">Platform Lingkungan #1 Indonesia</span>
            </div>

            <h1 class="text-5xl lg:text-6xl font-extrabold text-[#111827] leading-[1.1] tracking-tight mb-6">
                Bersama Kita <br>
                <span class="gradient-text">Hijaukan Bumi</span>
            </h1>
            <p class="text-lg text-gray-500 leading-relaxed mb-8 max-w-lg">
                Greennovate menghubungkan relawan, donatur, dan komunitas peduli alam untuk
                menciptakan dampak nyata bagi kelestarian lingkungan Indonesia.
            </p>

            {{-- CTA --}}
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('register') }}" id="hero-register-btn"
                   class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold px-7 py-3.5 rounded-full transition-all hover:shadow-xl hover:shadow-green-900/25 hover:-translate-y-0.5 text-base">
                    Daftar Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('login') }}" id="hero-login-btn"
                   class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:border-green-300 font-semibold px-7 py-3.5 rounded-full transition-all hover:shadow-md text-base">
                    Masuk
                </a>
            </div>

            {{-- Statistik Ringkas --}}
            <div class="flex items-center gap-6 mt-10 pt-10 border-t border-gray-100">
                <div class="stat-item text-center">
                    <div class="text-2xl font-extrabold text-[#0D8B41]">2.4K+</div>
                    <div class="text-xs text-gray-400 mt-0.5">Relawan Aktif</div>
                </div>
                <div class="w-px h-10 bg-gray-200"></div>
                <div class="stat-item text-center" style="animation-delay:.1s">
                    <div class="text-2xl font-extrabold text-[#0D8B41]">150+</div>
                    <div class="text-xs text-gray-400 mt-0.5">Program Berjalan</div>
                </div>
                <div class="w-px h-10 bg-gray-200"></div>
                <div class="stat-item text-center" style="animation-delay:.2s">
                    <div class="text-2xl font-extrabold text-[#0D8B41]">98%</div>
                    <div class="text-xs text-gray-400 mt-0.5">Dampak Terukur</div>
                </div>
            </div>
        </div>

        {{-- Hero Image (dengan fallback jika gagal load) --}}
        <div class="hero-image relative hidden lg:block">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-green-100">
                <img
                    src="{{ asset('images/hero.png') }}"
                    alt="Greennovate – Platform Lingkungan"
                    id="hero-img"
                    class="w-full h-full object-cover aspect-[4/3]"
                    onerror="this.onerror=null; this.src='{{ asset('images/fallback_placeholder.png') }}'; this.classList.add('opacity-60');"
                >
                {{-- Overlay gradient bottom --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent pointer-events-none"></div>
                {{-- Badge live counter --}}
                <div class="absolute bottom-4 left-4 right-4 flex justify-between">
                    <div class="animate-float bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg px-4 py-2.5 border border-green-100 flex items-center gap-2">
                        <span class="text-lg">🌱</span>
                        <div>
                            <div class="text-xs font-bold text-gray-800">+120 Pohon Ditanam</div>
                            <div class="text-xs text-gray-400">Hari ini</div>
                        </div>
                    </div>
                    <div class="animate-float-delay bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg px-4 py-2.5 border border-green-100 flex items-center gap-2">
                        <span class="text-lg">🤝</span>
                        <div>
                            <div class="text-xs font-bold text-gray-800">48 Relawan</div>
                            <div class="text-xs text-gray-400">Bergabung minggu ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== STATISTIK SECTION ========== --}}
<section class="py-16 bg-[#0D8B41]">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach([
                ['value' => '2.400+', 'label' => 'Relawan Terdaftar', 'icon' => '👥'],
                ['value' => '150+',   'label' => 'Program Aktif',     'icon' => '📋'],
                ['value' => '50.000+','label' => 'Pohon Ditanam',     'icon' => '🌳'],
                ['value' => '98%',    'label' => 'Dampak Terukur',    'icon' => '📊'],
            ] as $s)
            <div class="reveal">
                <div class="text-3xl mb-2">{{ $s['icon'] }}</div>
                <div class="text-3xl font-extrabold text-white mb-1">{{ $s['value'] }}</div>
                <div class="text-green-200 text-sm">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== MISI ========== --}}
<section id="mission" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16 reveal">
            <span class="inline-block text-sm font-semibold text-[#0D8B41] uppercase tracking-widest mb-3">Misi Kami</span>
            <h2 class="text-4xl font-extrabold text-[#111827] mb-4">Mengapa Greennovate?</h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                Kami percaya perubahan nyata dimulai dari komunitas yang terhubung dan bergerak bersama.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['icon'=>'🌍','title'=>'Dampak Nyata',       'desc'=>'Setiap aksi di Greennovate terukur dan terdokumentasi sehingga kamu tahu persis dampak yang kamu ciptakan.'],
                ['icon'=>'🤝','title'=>'Komunitas Peduli',   'desc'=>'Bergabung dengan ribuan relawan dan donatur yang sama-sama bersemangat menjaga kelestarian alam.'],
                ['icon'=>'📊','title'=>'Transparansi Penuh', 'desc'=>'Semua program, donasi, dan hasilnya tersaji secara terbuka. Tidak ada yang disembunyikan.'],
            ] as $i => $item)
            <div class="feature-card bg-gray-50 rounded-2xl p-8 border border-gray-100 reveal" data-delay="{{ $i * 120 }}">
                <div class="text-4xl mb-5">{{ $item['icon'] }}</div>
                <h3 class="text-xl font-bold text-[#111827] mb-3">{{ $item['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== KAMPANYE UNGGULAN ========== --}}
<section id="campaigns" class="py-24 bg-[#fafaf8]">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16 reveal">
            <span class="inline-block text-sm font-semibold text-[#0D8B41] uppercase tracking-widest mb-3">Kampanye</span>
            <h2 class="text-4xl font-extrabold text-[#111827] mb-4">Program Unggulan</h2>
            <p class="text-gray-500 text-lg max-w-xl mx-auto">Ikuti program nyata yang sudah berjalan dan buat perbedaan hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                [
                    'title'    => 'Penanaman 1000 Pohon',
                    'location' => 'Hutan Kota Jakarta',
                    'progress' => 72,
                    'joined'   => 248,
                    'quota'    => 350,
                    'tag'      => 'Reforestasi',
                    'color'    => 'green',
                    'emoji'    => '🌳',
                ],
                [
                    'title'    => 'Bersih Pantai Ancol',
                    'location' => 'Pantai Ancol, Jakarta',
                    'progress' => 55,
                    'joined'   => 110,
                    'quota'    => 200,
                    'tag'      => 'Kebersihan',
                    'color'    => 'blue',
                    'emoji'    => '🌊',
                ],
                [
                    'title'    => 'Daur Ulang Sampah',
                    'location' => 'Bandung, Jawa Barat',
                    'progress' => 88,
                    'joined'   => 176,
                    'quota'    => 200,
                    'tag'      => 'Daur Ulang',
                    'color'    => 'emerald',
                    'emoji'    => '♻️',
                ],
            ] as $i => $c)
            <div class="feature-card bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm reveal" data-delay="{{ $i * 100 }}">
                {{-- Kampanye image header --}}
                <div class="relative h-44 bg-gradient-to-br from-{{ $c['color'] }}-50 to-{{ $c['color'] }}-100 flex items-center justify-center overflow-hidden">
                    <span class="text-7xl opacity-30 select-none">{{ $c['emoji'] }}</span>
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/90 backdrop-blur-sm text-{{ $c['color'] }}-700 text-xs font-bold px-3 py-1 rounded-full border border-{{ $c['color'] }}-200">
                            {{ $c['tag'] }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-[#111827] text-lg mb-1">{{ $c['title'] }}</h3>
                    <div class="flex items-center gap-1 text-xs text-gray-400 mb-4">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $c['location'] }}
                    </div>
                    {{-- Progress bar --}}
                    <div class="mb-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span>{{ $c['joined'] }} / {{ $c['quota'] }} relawan</span>
                            <span class="font-semibold text-[#0D8B41]">{{ $c['progress'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-[#0D8B41] to-emerald-400 h-2 rounded-full transition-all duration-1000"
                                 style="width: {{ $c['progress'] }}%"></div>
                        </div>
                    </div>
                    <a href="{{ route('register') }}"
                       class="mt-4 w-full inline-flex items-center justify-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-semibold py-2.5 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-green-900/20">
                        Ikut Bergabung
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10 reveal">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-[#0D8B41] font-semibold hover:underline">
                Lihat semua kampanye
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ========== FITUR ========== --}}
<section id="features" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16 reveal">
            <span class="inline-block text-sm font-semibold text-[#0D8B41] uppercase tracking-widest mb-3">Fitur</span>
            <h2 class="text-4xl font-extrabold text-[#111827] mb-4">Semua yang Kamu Butuhkan</h2>
            <p class="text-gray-500 text-lg max-w-xl mx-auto">Satu platform lengkap untuk berkolaborasi, berkontribusi, dan menciptakan perubahan.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['emoji'=>'🗓️','title'=>'Program & Event',    'desc'=>'Temukan dan ikuti berbagai program lingkungan di sekitarmu.'],
                ['emoji'=>'💚','title'=>'Donasi Mudah',        'desc'=>'Dukung kampanye lingkungan dengan donasi yang aman dan transparan.'],
                ['emoji'=>'📈','title'=>'Dashboard Impak',     'desc'=>'Pantau kontribusimu secara real-time dengan visualisasi data.'],
                ['emoji'=>'🔔','title'=>'Notifikasi Cerdas',   'desc'=>'Dapatkan update program dan reminder langsung di akunmu.'],
                ['emoji'=>'🏆','title'=>'Sistem Reward',       'desc'=>'Kumpulkan poin dan badge untuk setiap aksi nyatamu.'],
                ['emoji'=>'🌐','title'=>'Komunitas Nasional',  'desc'=>'Terhubung dengan komunitas lingkungan dari seluruh Indonesia.'],
            ] as $i => $f)
            <div class="feature-card bg-gray-50 rounded-2xl p-6 border border-gray-100 reveal" data-delay="{{ $i * 80 }}">
                <div class="text-3xl mb-4">{{ $f['emoji'] }}</div>
                <h3 class="font-bold text-[#111827] mb-2">{{ $f['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== HOW IT WORKS ========== --}}
<section id="how-it-works" class="py-24 bg-[#fafaf8]">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16 reveal">
            <span class="inline-block text-sm font-semibold text-[#0D8B41] uppercase tracking-widest mb-3">Cara Kerja</span>
            <h2 class="text-4xl font-extrabold text-[#111827] mb-4">Mulai dalam 3 Langkah</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            @foreach([
                ['num'=>'01','title'=>'Daftar Akun',  'desc'=>'Buat akunmu gratis dalam hitungan detik. Tidak perlu kartu kredit.'],
                ['num'=>'02','title'=>'Pilih Program', 'desc'=>'Jelajahi berbagai program lingkungan dan pilih yang sesuai minatmu.'],
                ['num'=>'03','title'=>'Beri Dampak',   'desc'=>'Ikuti program dan pantau dampak nyata yang kamu ciptakan.'],
            ] as $i => $step)
            <div class="relative text-center reveal" data-delay="{{ $i * 150 }}">
                @if($i < 2)
                    <div class="hidden md:block step-line"></div>
                @endif
                <div class="w-14 h-14 rounded-full bg-[#0D8B41] text-white font-extrabold text-lg flex items-center justify-center mx-auto mb-5 shadow-lg shadow-green-900/20">
                    {{ $step['num'] }}
                </div>
                <h3 class="text-xl font-bold text-[#111827] mb-3">{{ $step['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== CTA SECTION ========== --}}
<section class="py-24 bg-gradient-to-br from-[#0D8B41] to-[#085c2b] relative overflow-hidden">
    <div class="orb w-[500px] h-[500px] bg-white/5 -top-[200px] right-[-100px]"></div>
    <div class="max-w-3xl mx-auto px-6 text-center relative z-10 reveal">
        <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">Siap Membuat Perbedaan?</h2>
        <p class="text-green-100 text-lg mb-10 leading-relaxed">
            Bergabunglah dengan ribuan orang yang sudah berkontribusi untuk bumi yang lebih hijau.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" id="cta-register-btn"
               class="inline-flex items-center gap-2 bg-white text-[#0D8B41] font-bold px-8 py-4 rounded-full hover:bg-green-50 transition-all hover:shadow-2xl hover:-translate-y-1 text-base">
                Daftar Gratis Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <a href="{{ route('login') }}" id="cta-login-btn"
               class="inline-flex items-center gap-2 border-2 border-white/30 text-white font-semibold px-8 py-4 rounded-full hover:bg-white/10 transition-all text-base">
                Sudah punya akun? Masuk
            </a>
        </div>
    </div>
</section>

@endsection
