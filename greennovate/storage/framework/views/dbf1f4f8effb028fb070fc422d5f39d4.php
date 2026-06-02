<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Panel Petugas - Greennovate'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind -->
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <script src="https://cdn.tailwindcss.com"></script>
    <?php endif; ?>

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f8faf9;
        }

        /* ── Sidebar ─────────────────────────────────────────────── */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fdf9 100%);
            border-right: 1px solid #e8f0eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid #e8f0eb;
        }

        .sidebar-menu {
            padding: 20px 14px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            padding: 0 18px;
            margin-bottom: 8px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 18px;
            border-radius: 12px;
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 4px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .menu-item:hover {
            background-color: #ecfdf5;
            color: #166534;
        }

        .menu-item.active {
            background: linear-gradient(135deg, #1a8245 0%, #15803d 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px -2px rgba(26, 130, 69, 0.3);
        }

        .menu-item.active svg {
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #e8f0eb;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px;
            margin-bottom: 8px;
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #166534;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
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
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background-color: #fef2f2;
            color: #ef4444;
        }

        /* ── Main Content ────────────────────────────────────────── */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-header {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid #e8f0eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .page-content {
            padding: 32px;
            flex: 1;
        }

        /* ── Mobile Overlay ──────────────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 35;
        }

        .hamburger-btn {
            display: none;
            padding: 8px;
            border-radius: 8px;
            color: #374151;
            transition: background 0.2s;
        }

        .hamburger-btn:hover {
            background: #f3f4f6;
        }

        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: 18px;
            right: 14px;
            padding: 6px;
            border-radius: 8px;
            color: #9ca3af;
            transition: all 0.2s;
        }

        .sidebar-close-btn:hover {
            background: #fef2f2;
            color: #ef4444;
        }

        /* ── Toast ───────────────────────────────────────────────── */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            max-width: 420px;
            transform: translateX(120%);
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .toast.success {
            border-left: 4px solid #22c55e;
        }

        .toast.error {
            border-left: 4px solid #ef4444;
        }

        .toast.info {
            border-left: 4px solid #3b82f6;
        }

        @keyframes slideIn {
            to { transform: translateX(0); }
        }

        @keyframes slideOut {
            to { transform: translateX(120%); opacity: 0; }
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .sidebar-close-btn {
                display: block;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .hamburger-btn {
                display: flex;
            }

            .page-content {
                padding: 20px 16px;
            }

            .top-header {
                padding: 0 16px;
            }
        }

        @media (max-width: 767px) {
            .page-content {
                padding: 16px 12px;
            }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="text-gray-800 antialiased">

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Close button (mobile) -->
        <button class="sidebar-close-btn" onclick="toggleSidebar()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Logo -->
        <div class="sidebar-header">
            <a href="<?php echo e(route('petugas.dashboard')); ?>" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#1a8b4b] to-[#15803d] flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                    </svg>
                </div>
                <span class="font-bold text-lg text-gray-900 tracking-tight">Petugas</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>

            <a href="<?php echo e(route('petugas.dashboard')); ?>" class="menu-item <?php echo e(request()->routeIs('petugas.dashboard') ? 'active' : ''); ?>">
                <svg class="w-[18px] h-[18px] <?php echo e(request()->routeIs('petugas.dashboard') ? 'text-white' : 'text-gray-400'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Kegiatan Saya
            </a>

            <a href="<?php echo e(route('petugas.semua-kegiatan')); ?>" class="menu-item <?php echo e(request()->routeIs('petugas.semua-kegiatan') ? 'active' : ''); ?>">
                <svg class="w-[18px] h-[18px] <?php echo e(request()->routeIs('petugas.semua-kegiatan') ? 'text-white' : 'text-gray-400'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
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
                    <?php echo e(strtoupper(substr(auth()->user()->name ?? 'P', 0, 1))); ?>

                </div>
                <div class="overflow-hidden">
                    <div class="font-bold text-sm text-gray-900 truncate"><?php echo e(auth()->user()->name ?? 'Petugas'); ?></div>
                    <div class="text-xs text-gray-500">Petugas Lapangan</div>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
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
            <div class="flex items-center gap-3">
                <button class="hamburger-btn" onclick="toggleSidebar()" id="hamburgerBtn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-[17px] font-bold text-gray-800"><?php echo $__env->yieldContent('header', 'Kegiatan Saya'); ?></h1>
            </div>
            <div class="text-sm text-gray-400 hidden sm:block">
                <?php echo e(now()->translatedFormat('l, d F Y')); ?>

            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // ── Sidebar Toggle (Mobile) ──
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        // ── Toast Notifications ──
        function showToast(message, type = 'success', duration = 4000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const icons = {
                success: '<svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                error: '<svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                info: '<svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            };

            toast.innerHTML = `
                ${icons[type] || icons.success}
                <span class="text-sm font-medium text-gray-700 flex-1">${message}</span>
                <button onclick="this.parentElement.style.animation='slideOut 0.3s forwards';setTimeout(()=>this.parentElement.remove(),300)" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s forwards';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\PPL-SI4705-KelA\greennovate\SI4705-KELA-main\greennovate\resources\views/layouts/petugas.blade.php ENDPATH**/ ?>