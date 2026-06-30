<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Greennovate')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback Tailwind CSS script for rapid prototyping if Vite isn't running -->
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>

    <!-- Alpine.js untuk Interaktivitas Dropdown -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-[#Fdfdfc] text-[#1b1b18] antialiased">
    <div class="min-h-screen flex flex-col pt-12 items-center">

        <!-- Navbar Area -->
        <header class="w-full max-w-5xl px-6 flex justify-between items-center mb-16">
            <a href="/" class="flex items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=Greennovate&background=0D8B41&color=fff&rounded=true" alt="Greennovate Logo" class="h-8 w-8">
                <span class="font-bold text-lg">Greennovate</span>
            </a>
            <nav class="hidden md:flex gap-6 text-sm text-gray-500 font-medium">
                <a href="{{ route('home') }}" class="hover:text-black">{{ __('Beranda') }}</a>
                <a href="{{ route('kegiatan.index') }}" class="hover:text-black">{{ __('Kegiatan') }}</a>
                <a href="#" class="hover:text-black">{{ __('Tentang') }}</a>
            </nav>
            <div class="flex items-center gap-4 text-sm font-medium">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-black">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="bg-[#1b7b43] text-white px-4 py-2 rounded-full hover:bg-green-700 transition">{{ __('Join Activity') }}</a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-black">{{ __('Dashboard') }}</a>

                    <!-- Notifikasi Icon -->
                    <a href="{{ route('notifikasi.index') }}" class="relative text-gray-500 hover:text-black mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php
                            $unreadNotifCount = \App\Models\Notifikasi::where('user_id', Auth::id())->belumDibaca()->count();
                        @endphp
                        @if($unreadNotifCount > 0)
                            <span class="notif-badge-count absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-[#Fdfdfc]">
                                {{ $unreadNotifCount }}
                            </span>
                        @else
                            <span class="notif-badge-count absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-[#Fdfdfc]" style="display: none;">
                                0
                            </span>
                        @endif
                    </a>

                    <!-- Profile Dropdown (Menggunakan Alpine.js) -->
                    <div class="relative" x-data="{ open: false }">

                        <!-- Avatar -->
                        <button @click="open = !open"
                            class="w-10 h-10 rounded-full bg-[#1b7b43] flex items-center justify-center text-white font-bold cursor-pointer focus:outline-none">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                            style="display: none;">

                            <!-- Info user -->
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <!-- Menu Links -->
                            <a href="{{ route('qr-scan.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 font-medium text-[#0D8B41]">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1-1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    {{ __('Scan QR Code') }}
                                </span>
                            </a>
                            <a href="{{ route('riwayat.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">
                                {{ __('Riwayat') }}
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">
                                {{ __('Profile') }}
                            </a>
                            <a href="{{ route('chat.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 text-green-700 flex justify-between items-center">
                                <span>{{ __('Hubungi Admin') }}</span>
                                @if(isset($unreadChatCount) && $unreadChatCount > 0)
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadChatCount }}</span>
                                @endif
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 text-red-600">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </header>

        <!-- Main Content -->
        <main class="w-full flex-1 flex flex-col items-center">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>