<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Greennovate'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <!-- Fallback Tailwind CSS script for rapid prototyping if Vite isn't running -->
        <script src="https://cdn.tailwindcss.com"></script>
    <?php endif; ?>

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
                <a href="<?php echo e(route('home')); ?>" class="hover:text-black"><?php echo e(__('Beranda')); ?></a>
                <a href="<?php echo e(route('kegiatan.index')); ?>" class="hover:text-black"><?php echo e(__('Kegiatan')); ?></a>
                <a href="#" class="hover:text-black"><?php echo e(__('Tentang')); ?></a>
            </nav>
            <div class="flex items-center gap-4 text-sm font-medium">
                <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-black"><?php echo e(__('Login')); ?></a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-[#1b7b43] text-white px-4 py-2 rounded-full hover:bg-green-700 transition"><?php echo e(__('Join Activity')); ?></a>
                <?php else: ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-black"><?php echo e(__('Dashboard')); ?></a>

                    <!-- Profile Dropdown (Menggunakan Alpine.js) -->
                    <div class="relative" x-data="{ open: false }">

                        <!-- Avatar -->
                        <button @click="open = !open"
                            class="w-10 h-10 rounded-full bg-[#1b7b43] flex items-center justify-center text-white font-bold cursor-pointer focus:outline-none">
                            <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

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
                                <p class="text-sm font-semibold text-gray-800"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
                            </div>

                            <!-- Menu Links -->
                            <a href="<?php echo e(route('profile.edit')); ?>"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">
                                <?php echo e(__('Profile')); ?>

                            </a>
                            <a href="<?php echo e(route('riwayat.index')); ?>"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">
                                <?php echo e(__('Riwayat Partisipasi')); ?>

                            </a>

                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 text-red-600">
                                    <?php echo e(__('Logout')); ?>

                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Main Content -->
        <main class="w-full flex-1 flex flex-col items-center">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /Users/awangwahyu/Documents/GitHub/SI4705-KELA/greennovate/resources/views/layouts/auth.blade.php ENDPATH**/ ?>