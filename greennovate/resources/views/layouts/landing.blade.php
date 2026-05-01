<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Greennovate – Platform kolaborasi lingkungan yang menghubungkan relawan, donatur, dan komunitas peduli alam untuk menciptakan dampak nyata bagi kelestarian bumi.">
        <title>@yield('title', 'Greennovate – Bersama Menghijaukan Bumi')</title>

        <!-- Preconnect for performance -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <style>
            :root {
                --green-primary: #0D8B41;
                --green-dark: #085c2b;
                --green-light: #e8f5ee;
                --text-dark: #111827;
                --text-muted: #6b7280;
                --bg-page: #f0f0eb;
            }

            * { scroll-behavior: smooth; box-sizing: border-box; }

            body {
                font-family: 'Instrument Sans', sans-serif;
                background-color: var(--bg-page);
                color: var(--text-dark);
                -webkit-font-smoothing: antialiased;
            }

            /* Navbar */
            .navbar-glass {
                background: rgba(240, 240, 235, 0.92);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-bottom: 1px solid rgba(0,0,0,0.06);
            }

            /* Reveal animation */
            .reveal {
                opacity: 0;
                transform: translateY(24px);
                transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1);
            }
            .reveal.visible { opacity: 1; transform: translateY(0); }

            /* Hero animations */
            @keyframes fade-up {
                from { opacity: 0; transform: translateY(30px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes fade-left {
                from { opacity: 0; transform: translateX(30px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            .hero-copy  { animation: fade-up  0.8s cubic-bezier(0.16,1,0.3,1) both; }
            .hero-image { animation: fade-left 1s  cubic-bezier(0.16,1,0.3,1) 0.15s both; }

            /* Stat card hover */
            .stat-card {
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }
            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 28px rgba(0,0,0,0.08);
            }

            /* Feature card hover */
            .feature-card {
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 16px 32px rgba(13,139,65,0.10);
            }

            /* Green heading word */
            .text-green-word { color: var(--green-primary); }

            /* Step connector line */
            .step-line {
                position: absolute;
                top: 26px;
                left: calc(50% + 26px);
                width: calc(100% - 52px);
                height: 1px;
                background: linear-gradient(90deg, #0D8B41 0%, #bbf7d0 100%);
            }

            /* Gradient orb */
            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(70px);
                pointer-events: none;
                z-index: 0;
            }

            /* Animate ping for badge */
            @keyframes ping-slow {
                75%, 100% { transform: scale(2); opacity: 0; }
            }
            .animate-ping-slow { animation: ping-slow 2s cubic-bezier(0,0,0.2,1) infinite; }

            /* Float for hero image badges */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-5px); }
            }
            .animate-float { animation: float 3s ease-in-out infinite; }
            .animate-float-delay { animation: float 3.5s ease-in-out 0.8s infinite; }
        </style>
        @yield('styles')
    </head>
    <body>
        <!-- Sticky Navbar -->
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 navbar-glass transition-all duration-300">
            <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-[#0D8B41] flex items-center justify-center shadow group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-[#111827] tracking-tight">Greennovate</span>
                </a>

                <!-- Nav Links (center) -->
                <div class="hidden md:flex items-center gap-7 text-sm font-medium">
                    <a href="/" id="nav-beranda"
                       class="{{ request()->is('/') ? 'text-[#0D8B41] font-semibold' : 'text-gray-500 hover:text-[#0D8B41]' }} transition-colors">
                        Beranda
                    </a>
                    <a href="#" id="nav-kegiatan"
                       class="text-gray-500 hover:text-[#0D8B41] transition-colors">
                        Kegiatan
                    </a>
                    <a href="#" id="nav-tentang"
                       class="text-gray-500 hover:text-[#0D8B41] transition-colors">
                        Tentang
                    </a>
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" id="nav-login-btn"
                           class="text-sm font-semibold text-gray-600 hover:text-[#0D8B41] transition-colors px-3 py-1.5">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" id="nav-register-btn"
                           class="bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-semibold px-4 py-2 rounded-full transition-all hover:shadow-lg hover:shadow-green-900/20">
                            Daftar Gratis
                        </a>
                    @else
                        {{-- Donasi --}}
                        <a href="#" id="nav-donasi-link"
                           class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-[#0D8B41] transition-colors px-2 py-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Donasi
                        </a>

                        {{-- Riwayat --}}
                        <a href="#" id="nav-riwayat-link"
                           class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-[#0D8B41] transition-colors px-2 py-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Riwayat
                        </a>

                        {{-- User avatar + name --}}
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'petugas' ? route('petugas.dashboard') : route('dashboard')) }}" id="nav-user-pill"
                           class="flex items-center gap-2 rounded-full px-2 py-1 hover:bg-gray-100 transition-colors ml-1">
                            <div class="w-7 h-7 rounded-full bg-[#0D8B41] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                        </a>

                        {{-- Logout icon --}}
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" id="nav-logout-btn" title="Keluar"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-[#0a1f14] text-white">
            <div class="max-w-6xl mx-auto px-6 py-16">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                    <!-- Brand -->
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-2.5 mb-4">
                            <div class="w-9 h-9 rounded-xl bg-[#0D8B41] flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                                </svg>
                            </div>
                            <span class="font-bold text-lg">Greennovate</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                            Platform kolaborasi lingkungan yang menghubungkan relawan, donatur, dan komunitas peduli alam untuk dampak nyata.
                        </p>
                        <div class="flex gap-3 mt-6">
                            <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#0D8B41] flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#0D8B41] flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#0D8B41] flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Links Platform -->
                    <div>
                        <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Platform</h4>
                        <ul class="space-y-2.5">
                            <li><a href="#mission" class="text-gray-400 hover:text-white text-sm transition-colors">Tentang Kami</a></li>
                            <li><a href="#features" class="text-gray-400 hover:text-white text-sm transition-colors">Fitur</a></li>
                            <li><a href="#how-it-works" class="text-gray-400 hover:text-white text-sm transition-colors">Cara Kerja</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Daftar</a></li>
                        </ul>
                    </div>

                    <!-- Links Legal -->
                    <div>
                        <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Legal</h4>
                        <ul class="space-y-2.5">
                            <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Kebijakan Privasi</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Kontak</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-gray-500 text-sm">© {{ date('Y') }} Greennovate. Dibuat dengan ❤️ untuk bumi yang lebih hijau.</p>
                    <p class="text-gray-600 text-xs">Made by Team SI4705 KelA</p>
                </div>
            </div>
        </footer>

        <!-- Scroll reveal script -->
        <script>
            // Navbar scroll effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    navbar.classList.add('shadow-md');
                } else {
                    navbar.classList.remove('shadow-md');
                }
            });

            // Reveal on scroll
            const revealEls = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(el => {
                    if (el.isIntersecting) {
                        // Respect stagger delay set via data-delay attribute
                        const delay = el.target.dataset.delay || 0;
                        setTimeout(() => el.target.classList.add('visible'), delay);
                        observer.unobserve(el.target);
                    }
                });
            }, { threshold: 0.12 });
            revealEls.forEach(el => observer.observe(el));
        </script>

        {{-- Flash Toast Notification --}}
        @if(session('success'))
        <div id="flash-toast"
             class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white border border-green-200 shadow-xl rounded-2xl px-5 py-4 text-sm font-medium text-gray-700 transition-all duration-500"
             style="animation: slideInToast 0.4s ease-out both;">
            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span>{{ session('success') }}</span>
        </div>
        <style>
            @keyframes slideInToast {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }
        </style>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('flash-toast');
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(20px)';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3500);
        </script>
        @endif

        @yield('scripts')
    </body>
</html>
