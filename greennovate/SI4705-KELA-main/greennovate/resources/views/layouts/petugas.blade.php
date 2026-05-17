<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Petugas - Greennovate')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #fbfbfb;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
        }

        .sidebar-header {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid #f8fafc;
        }

        .sidebar-menu {
            padding: 24px 16px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 9999px;
            color: #64748b;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .menu-item:hover {
            background-color: #f8fafc;
            color: #334155;
        }

        .menu-item.active {
            background-color: #1a8245;
            color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(26, 130, 69, 0.2);
        }

        .menu-item.active svg {
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #f8fafc;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px;
            margin-bottom: 8px;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #e2f5ea;
            color: #1a8245;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background-color: #fef2f2;
            color: #ef4444;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-header {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .page-content {
            padding: 32px;
            flex: 1;
        }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Logo -->
        <div class="sidebar-header">
            <a href="/" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#1a8b4b] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                    </svg>
                </div>
                <span class="font-bold text-lg text-gray-900 tracking-tight">Petugas</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-menu">
            <a href="{{ route('petugas.dashboard') }}" class="menu-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('petugas.dashboard') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Kegiatan Saya
            </a>
            
            <a href="#" class="menu-item">
                <svg class="w-[18px] h-[18px] text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Semua Kegiatan
            </a>
            
            <a href="#" class="menu-item">
                <svg class="w-[18px] h-[18px] text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Sumbangan
            </a>
        </nav>

        <!-- Footer / Profile -->
        <div class="sidebar-footer">
            <div class="profile-card">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="font-bold text-sm text-gray-900 truncate">{{ auth()->user()->name ?? 'Agus Pratama' }}</div>
                    <div class="text-xs text-gray-500">Petugas Lapangan</div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="text-[17px] font-bold text-gray-800">@yield('header', 'Panel Petugas')</h1>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>

</body>
</html>
