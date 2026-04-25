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
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#Fdfdfc] text-[#1b1b18] antialiased">
    <div class="min-h-screen flex flex-col pt-12 items-center">

        <!-- Navbar Area Placeholder -->
        <header class="w-full max-w-5xl px-6 flex justify-between items-center mb-16">
            <a href="/" class="flex items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=Greennovate&background=0D8B41&color=fff&rounded=true"
                    alt="Greennovate Logo" class="h-8 w-8">
                <span class="font-bold text-lg">Greennovate</span>
            </a>
            <nav class="hidden md:flex gap-6 text-sm text-gray-500 font-medium">
                <a href="/" class="hover:text-black">Beranda</a>
                <a href="#" class="hover:text-black">Kegiatan</a>
                <a href="#" class="hover:text-black">Tentang</a>
            </nav>
            <div class="flex items-center gap-4 text-sm font-medium">
                <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-black"><?php echo e(__('Login')); ?></a>
                    <a href="<?php echo e(route('register')); ?>"
                        class="bg-[#1b7b43] text-white px-4 py-2 rounded-full hover:bg-green-700 transition"><?php echo e(__('Join Activity')); ?></a>
                <?php else: ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-black"><?php echo e(__('Dashboard')); ?></a>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="text-gray-500 hover:text-black"><?php echo e(__('Profile')); ?></a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="bg-red-50 text-red-600 px-4 py-2 rounded-full hover:bg-red-100 transition"><?php echo e(__('Logout')); ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </header>

        <main class="w-full flex-1 flex flex-col items-center">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH /Users/awangwahyu/Downloads/SI4705-KELA-main/greennovate/resources/views/layouts/auth.blade.php ENDPATH**/ ?>