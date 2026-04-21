@extends('layouts.landing')

@section('title', 'Greennovate – Bersama Menghijaukan Masa Depan Bumi')

@section('styles')
<style>
    /* ───────────────────────── HERO ───────────────────────── */
    #hero {
        min-height: 100vh;
        background: linear-gradient(160deg, #f0fdf4 0%, #fafaf8 40%, #ecfdf5 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        padding-top: 80px;
    }

    /* ─────────────────────── STATS BAR ────────────────────── */
    .stat-card {
        border-left: 3px solid #0D8B41;
    }

    /* ──────────────────────── MISSION ─────────────────────── */
    #mission {
        background: white;
    }

    /* ─────────────────────── FEATURES ─────────────────────── */
    #features {
        background: linear-gradient(180deg, #f0fdf4 0%, #fafaf8 100%);
    }

    .feature-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        flex-shrink: 0;
    }

    /* ──────────────────────── HOW IT WORKS ────────────────── */
    #how-it-works {
        background: white;
    }

    .step-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #0D8B41;
        color: white;
        font-weight: 800;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: 0 8px 24px rgba(13,139,65,0.35);
        position: relative;
        z-index: 1;
    }

    /* ──────────────────────── TESTIMONIAL ─────────────────── */
    #testimonials {
        background: linear-gradient(135deg, #0a1f14 0%, #0D8B41 100%);
    }

    .testimonial-card {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        transition: transform 0.3s ease, background 0.3s ease;
    }
    .testimonial-card:hover {
        transform: translateY(-4px);
        background: rgba(255,255,255,0.13);
    }

    /* ───────────── FINAL CTA ─────────────────────────────── */
    #cta-final {
        background: #fafaf8;
    }
    .cta-card {
        background: linear-gradient(135deg, #0D8B41 0%, #15803d 50%, #065f46 100%);
        border-radius: 2rem;
        position: relative;
        overflow: hidden;
    }
    .cta-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
</style>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     1. HERO SECTION
══════════════════════════════════════════════════════════════ --}}
<section id="hero">
    <!-- Decorative blobs -->
    <div class="orb w-[500px] h-[500px] bg-green-200/40 -top-40 -left-40"></div>
    <div class="orb w-[350px] h-[350px] bg-emerald-300/30 bottom-0 right-0"></div>
    <div class="orb w-[200px] h-[200px] bg-teal-200/40 top-1/2 left-1/3"></div>

    <div class="max-w-6xl mx-auto px-6 w-full py-16 relative z-10 grid lg:grid-cols-2 gap-16 items-center">

        <!-- Left: Copy & CTA -->
        <div class="hero-copy flex flex-col gap-6">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 rounded-full px-4 py-2 text-sm font-semibold text-green-700 w-max shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping-slow absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#0D8B41]"></span>
                </span>
                Platform Aksi Lingkungan #1 Indonesia
            </div>

            <!-- Headline -->
            <h1 class="text-5xl xl:text-6xl font-black text-[#111827] leading-[1.08] tracking-tight">
                Bersama <span class="gradient-text">Menghijaukan</span><br>
                Masa Depan Bumi
            </h1>

            <p class="text-lg text-gray-500 leading-relaxed max-w-lg">
                Greennovate adalah platform kolaborasi yang menghubungkan relawan, donatur, dan komunitas peduli lingkungan untuk menciptakan dampak nyata bagi kelestarian alam secara berkelanjutan.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4 mt-2">
                <a href="{{ route('register') }}" id="hero-register-btn"
                   class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white px-8 py-4 rounded-full font-bold text-base transition-all hover:scale-105 hover:-translate-y-0.5 shadow-xl shadow-green-900/25">
                    Daftar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('login') }}" id="hero-login-btn"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-8 py-4 rounded-full font-bold text-base transition-all hover:scale-105 hover:-translate-y-0.5 shadow-md hover:border-[#0D8B41] hover:text-[#0D8B41]">
                    Masuk
                </a>
            </div>

            <!-- Social proof -->
            <div class="flex items-center gap-3 mt-2">
                <div class="flex -space-x-2">
                    @foreach(['4CAF50','2E7D32','1B5E20','388E3C'] as $color)
                    <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white" style="background-color: #{{ $color }}">
                        {{ chr(rand(65,90)) }}
                    </div>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 font-medium"><span class="text-[#111827] font-bold">2,000+</span> relawan sudah bergabung</p>
            </div>
        </div>

        <!-- Right: Hero Image -->
        <div class="hero-image relative">
            <!-- Glow backdrop -->
            <div class="absolute -inset-4 bg-gradient-to-tr from-[#0D8B41]/25 to-teal-300/15 rounded-[2.5rem] blur-3xl opacity-70"></div>

            <!-- Image container -->
            <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/60 bg-white/80">
                <img
                    src="{{ asset('images/hero.png') }}"
                    alt="Relawan Greennovate menanam pohon bersama komunitas lingkungan"
                    class="w-full h-auto object-cover object-center aspect-[4/3] lg:aspect-[4/5] transform hover:scale-[1.03] transition duration-[1400ms] ease-out"
                    onerror="this.onerror=null; this.parentElement.classList.add('placeholder-bg');"
                    id="hero-img"
                >
                <!-- Overlay if image fails to load (handled by JS below) -->
            </div>

            <!-- Floating badge: Live Campaign -->
            <div class="animate-float absolute top-5 right-5 bg-white/95 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-xl border border-white/80 flex items-center gap-2.5 z-10">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[#0D8B41]"></span>
                </span>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide leading-none mb-0.5">Status</p>
                    <p class="text-sm font-black text-gray-900">Live Campaign</p>
                </div>
            </div>

            <!-- Floating badge: Trees planted -->
            <div class="animate-float-delay absolute bottom-5 left-5 bg-white/95 backdrop-blur-md px-5 py-4 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] border border-white/80 flex items-center gap-3 z-10">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-[#0D8B41]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-0.5">Dampak Nyata</p>
                    <p class="text-base font-black text-gray-900 leading-tight">15,000+ Pohon</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     2. STATISTIK STRIP
══════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-y border-gray-100 py-12 shadow-sm">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach([
                ['2,000+', 'Relawan Aktif', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['50+', 'Komunitas Hijau', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['15k', 'Pohon Ditanam', 'M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z'],
                ['120+', 'Event Selesai', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ] as [$number, $label, $icon])
            <div class="reveal stat-card pl-4 py-1 group" data-delay="{{ $loop->index * 100 }}">
                <span class="text-4xl font-black text-[#111827] group-hover:text-[#0D8B41] transition-colors duration-300 block">{{ $number }}</span>
                <span class="text-sm font-medium text-gray-500 mt-1 block">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     3. MISI / TENTANG KAMI
══════════════════════════════════════════════════════════════ --}}
<section id="mission" class="py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Image left -->
            <div class="reveal relative" data-delay="0">
                <div class="absolute -inset-3 bg-gradient-to-br from-green-200/40 to-emerald-100/20 rounded-[2rem] blur-2xl"></div>
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-gray-100 bg-gradient-to-br from-green-50 to-emerald-50 aspect-square flex items-center justify-center">
                    <svg class="w-48 h-48 text-[#0D8B41]/20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15v-4H7l5-8v4h4l-5 8z"/>
                    </svg>
                    <!-- Mission visual grid -->
                    <div class="absolute inset-0 grid grid-cols-2 grid-rows-2 gap-3 p-6">
                        @foreach([
                            ['🌱', 'Tanam Pohon', 'bg-green-100'],
                            ['♻️', 'Daur Ulang', 'bg-blue-50'],
                            ['🌊', 'Bersih Laut', 'bg-cyan-50'],
                            ['☀️', 'Energi Terbarukan', 'bg-yellow-50'],
                        ] as [$emoji, $label, $bg])
                        <div class="rounded-2xl {{ $bg }} flex flex-col items-center justify-center gap-2 p-4 border border-white shadow-sm hover:scale-105 transition-transform cursor-default">
                            <span class="text-3xl">{{ $emoji }}</span>
                            <span class="text-xs font-semibold text-gray-600 text-center leading-tight">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Content right -->
            <div class="reveal flex flex-col gap-6" data-delay="150">
                <div class="inline-flex items-center gap-2 text-[#0D8B41] font-semibold text-sm uppercase tracking-widest">
                    <div class="h-0.5 w-8 bg-[#0D8B41]"></div>
                    Tentang Greennovate
                </div>
                <h2 class="text-4xl font-black text-[#111827] leading-tight">
                    Misi Kami: Dampak<br>Lingkungan <span class="gradient-text">Nyata</span>
                </h2>
                <p class="text-gray-500 leading-relaxed">
                    Greennovate hadir sebagai ekosistem digital yang memudahkan siapa saja untuk berkontribusi pada kelestarian lingkungan. Kami percaya bahwa perubahan dimulai dari gerakan kolektif yang terorganisir dan terukur.
                </p>
                <p class="text-gray-500 leading-relaxed">
                    Melalui platform kami, setiap individu dapat bergabung dalam program penanaman pohon, kampanye daur ulang, pembersihan pantai, dan berbagai inisiatif hijau lainnya—semua dalam satu tempat yang mudah diaksesnya.
                </p>

                <!-- Mission pillars -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                    @foreach([
                        ['🌿', 'Berkelanjutan', 'Program jangka panjang berkesinambungan'],
                        ['🤝', 'Kolaboratif', 'Komunitas relawan saling mendukung'],
                        ['📊', 'Terukur', 'Dampak dilacak & dilaporkan transparan'],
                        ['🏆', 'Berdampak', 'Hasil nyata untuk lingkungan'],
                    ] as [$emoji, $title, $desc])
                    <div class="flex gap-3 p-4 rounded-2xl bg-green-50/60 border border-green-100 hover:bg-green-50 transition-colors">
                        <span class="text-2xl mt-0.5">{{ $emoji }}</span>
                        <div>
                            <p class="font-bold text-[#111827] text-sm">{{ $title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 leading-snug">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     4. FITUR UNGGULAN
══════════════════════════════════════════════════════════════ --}}
<section id="features" class="py-24">
    <div class="max-w-6xl mx-auto px-6">
        <!-- Section header -->
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <div class="inline-flex items-center gap-2 text-[#0D8B41] font-semibold text-sm uppercase tracking-widest mb-4">
                <div class="h-0.5 w-8 bg-[#0D8B41]"></div>
                Fitur Platform
                <div class="h-0.5 w-8 bg-[#0D8B41]"></div>
            </div>
            <h2 class="text-4xl font-black text-[#111827] leading-tight mb-4">
                Semua yang Kamu Butuhkan<br>untuk <span class="gradient-text">Aksi Lingkungan</span>
            </h2>
            <p class="text-gray-500 leading-relaxed">
                Greennovate menyediakan berbagai fitur canggih untuk membantu kamu berkolaborasi, memantau dampak, dan bergabung dengan komunitas peduli lingkungan.
            </p>
        </div>

        <!-- Features grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                [
                    'bg-green-100', 'text-green-700',
                    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                    'Manajemen Event', 'Buat, kelola, dan ikuti berbagai kegiatan lingkungan yang terstruktur dengan mudah.'
                ],
                [
                    'bg-blue-100', 'text-blue-700',
                    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'Komunitas Relawan', 'Bergabung dan terhubung dengan ribuan relawan aktif yang semangat menjaga lingkungan.'
                ],
                [
                    'bg-amber-100', 'text-amber-700',
                    'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'Pantau Dampak', 'Lacak kontribusimu secara real-time: pohon ditanam, sampah dikumpulkan, dan lainnya.'
                ],
                [
                    'bg-purple-100', 'text-purple-700',
                    'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                    'Donasi Transparan', 'Salurkan donasi dengan jelas ke program pilihan, dilaporkan secara transparan dan akuntabel.'
                ],
                [
                    'bg-rose-100', 'text-rose-700',
                    'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                    'Leaderboard & Reward', 'Raih poin, badge, dan hadiah menarik atas kontribusi nyatamu di platform.'
                ],
                [
                    'bg-teal-100', 'text-teal-700',
                    'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                    'Notifikasi Cerdas', 'Dapatkan pemberitahuan event baru, pengingat kegiatan, dan update kampanye tepat waktu.'
                ],
            ] as [$iconBg, $iconColor, $iconPath, $title, $desc])
            <div class="reveal feature-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm" data-delay="{{ $loop->index * 80 }}">
                <div class="feature-icon {{ $iconBg }}">
                    <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-[#111827] text-lg mb-2">{{ $title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     5. CARA KERJA
══════════════════════════════════════════════════════════════ --}}
<section id="how-it-works" class="py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <div class="inline-flex items-center gap-2 text-[#0D8B41] font-semibold text-sm uppercase tracking-widest mb-4">
                <div class="h-0.5 w-8 bg-[#0D8B41]"></div>
                Cara Kerja
                <div class="h-0.5 w-8 bg-[#0D8B41]"></div>
            </div>
            <h2 class="text-4xl font-black text-[#111827] leading-tight mb-4">
                3 Langkah Mudah untuk<br><span class="gradient-text">Mulai Berdampak</span>
            </h2>
            <p class="text-gray-500">Bergabung dengan Greennovate sangat mudah—hanya butuh beberapa menit untuk memulai perjalanan hijau kamu.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-10 relative">
            <!-- Connector lines (desktop only, positioned via CSS) -->
            <div class="hidden md:block absolute top-7 left-[calc(16.67%+28px)] right-[calc(16.67%+28px)] h-0.5 bg-gradient-to-r from-[#0D8B41]/30 via-[#0D8B41]/60 to-[#0D8B41]/30 z-0"></div>

            @foreach([
                ['01', 'Daftar Akun', 'Buat akun gratis hanya dengan email atau nomor HP. Proses pendaftaran selesai dalam hitungan detik.', '👤'],
                ['02', 'Pilih Kegiatan', 'Jelajahi berbagai program dan event lingkungan di daerahmu. Pilih yang paling sesuai minat dan jadwalmu.', '🔍'],
                ['03', 'Berkontribusi', 'Ikuti kegiatan, dokumentasikan aksimu, dan lihat dampak nyata yang kamu hasilkan bagi lingkungan!', '🌿'],
            ] as [$num, $title, $desc, $emoji])
            <div class="reveal flex flex-col items-center text-center group" data-delay="{{ $loop->index * 120 }}">
                <div class="step-circle group-hover:scale-110 transition-transform mb-4 shadow-lg shadow-green-900/30">
                    {{ $num }}
                </div>
                <div class="text-3xl mb-3">{{ $emoji }}</div>
                <h3 class="font-black text-[#111827] text-xl mb-2">{{ $title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     6. TESTIMONI
══════════════════════════════════════════════════════════════ --}}
<section id="testimonials" class="py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-14 reveal">
            <div class="inline-flex items-center gap-2 text-green-300 font-semibold text-sm uppercase tracking-widest mb-4">
                <div class="h-0.5 w-8 bg-green-400"></div>
                Testimoni
                <div class="h-0.5 w-8 bg-green-400"></div>
            </div>
            <h2 class="text-4xl font-black text-white leading-tight">
                Apa Kata Para Relawan<br><span class="text-green-300">Greennovate?</span>
            </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['Andi Pratama', 'Relawan Bandung', 'Greennovate mengubah cara saya berkontribusi untuk lingkungan. Kini saya bisa temukan kegiatan penanaman pohon setiap minggu!', 'A'],
                ['Sari Dewi', 'Donatur Aktif', 'Platform ini sangat transparan. Saya bisa melihat langsung dampak donasi saya—sudah 500 pohon ditanam atas nama saya!', 'S'],
                ['Budi Santoso', 'Koordinator Komunitas', 'Sebagai koordinator, fitur manajemen event Greennovate sangat membantu. Tim kami jadi lebih terorganisir dan dampaknya terukur.', 'B'],
            ] as [$name, $role, $quote, $initial])
            <div class="reveal testimonial-card rounded-3xl p-7 flex flex-col gap-5" data-delay="{{ $loop->index * 100 }}">
                <!-- Stars -->
                <div class="flex gap-1">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <!-- Quote -->
                <p class="text-white/85 text-sm leading-relaxed italic">"{{ $quote }}"</p>
                <!-- Author -->
                <div class="flex items-center gap-3 mt-auto">
                    <div class="w-10 h-10 rounded-full bg-[#0D8B41]/60 border border-white/30 flex items-center justify-center text-white font-bold text-sm">
                        {{ $initial }}
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">{{ $name }}</p>
                        <p class="text-green-300/80 text-xs">{{ $role }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     7. CALL TO ACTION FINAL
══════════════════════════════════════════════════════════════ --}}
<section id="cta-final" class="py-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="cta-card reveal px-8 py-16 md:py-20 text-center">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl mx-auto flex flex-col items-center gap-6">
                <span class="text-5xl">🌏</span>
                <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                    Siap Bergabung dan<br>Membuat Perubahan?
                </h2>
                <p class="text-green-100/80 text-lg leading-relaxed">
                    Bersama Greennovate, setiap langkah kecilmu punya dampak besar bagi bumi. Daftar gratis sekarang dan mulai perjalanan hijaumu!
                </p>
                <div class="flex flex-wrap justify-center gap-4 mt-2">
                    <a href="{{ route('register') }}" id="cta-register-btn"
                       class="inline-flex items-center gap-2 bg-white text-[#0D8B41] font-black px-10 py-4 rounded-full text-base hover:bg-green-50 transition-all hover:scale-105 hover:-translate-y-0.5 shadow-xl">
                        Daftar Sekarang — Gratis!
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" id="cta-login-btn"
                       class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold px-10 py-4 rounded-full text-base border border-white/30 transition-all hover:scale-105 hover:-translate-y-0.5">
                        Sudah Punya Akun? Masuk
                    </a>
                </div>
                <p class="text-green-200/60 text-sm">Tanpa biaya, tanpa komitmen. Mulai kapan saja. 🌱</p>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Image error fallback: show placeholder div with gradient
    document.getElementById('hero-img')?.addEventListener('error', function() {
        const container = this.parentElement;
        container.classList.add('placeholder-bg');
        this.style.display = 'none';
        const placeholder = document.createElement('div');
        placeholder.className = 'w-full aspect-[4/3] lg:aspect-[4/5] flex flex-col items-center justify-center gap-4 bg-gradient-to-br from-green-50 to-emerald-100';
        placeholder.innerHTML = `
            <svg class="w-20 h-20 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-green-400 font-medium text-sm">Gambar tidak tersedia</p>
        `;
        container.insertBefore(placeholder, this);
    });
</script>
@endsection
