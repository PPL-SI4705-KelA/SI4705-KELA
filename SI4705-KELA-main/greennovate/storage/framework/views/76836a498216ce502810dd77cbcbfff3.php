<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404 – Halaman Tidak Ditemukan | Greennovate</title>
        <meta name="description" content="Halaman yang Anda cari tidak ditemukan.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
            <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <?php else: ?>
            <script src="https://cdn.tailwindcss.com"></script>
        <?php endif; ?>

        <style>
            body { font-family: 'Instrument Sans', sans-serif; }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-16px) rotate(3deg); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(27, 123, 67, 0.3); }
                50% { box-shadow: 0 0 40px rgba(27, 123, 67, 0.6); }
            }
            .float-animation { animation: float 4s ease-in-out infinite; }
            .glow-animation { animation: pulse-glow 3s ease-in-out infinite; }
        </style>
    </head>
    <body class="bg-[#f8fdf9] text-[#1b1b18] antialiased min-h-screen flex flex-col items-center justify-center p-6">

        
        <div class="fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-green-100 opacity-50 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-emerald-100 opacity-50 blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center max-w-md w-full">

            
            <a href="/" class="inline-flex items-center gap-2 mb-12">
                <img src="https://ui-avatars.com/api/?name=Greennovate&background=0D8B41&color=fff&rounded=true" alt="Greennovate Logo" class="h-8 w-8">
                <span class="font-bold text-lg">Greennovate</span>
            </a>

            
            <div class="float-animation mb-8 relative inline-block">
                <div class="w-36 h-36 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 mx-auto flex items-center justify-center glow-animation">
                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <div class="absolute -top-2 -right-2 text-2xl">🌿</div>
                <div class="absolute -bottom-1 -left-3 text-xl">🍃</div>
            </div>

            
            <div class="mb-2">
                <span class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-emerald-600 leading-none">
                    404
                </span>
            </div>

            
            <h1 class="text-2xl font-bold text-gray-800 mb-3">
                Halaman Tidak Ditemukan
            </h1>
            <p class="text-gray-500 text-base mb-2">
                Sepertinya kegiatan atau halaman yang Anda cari tidak ada,<br>
                telah dipindahkan, atau belum tersedia.
            </p>
            <p class="text-gray-400 text-sm mb-10">
                Jangan khawatir, masih banyak kegiatan menarik lainnya! 🌱
            </p>

            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?php echo e(url('/kegiatan')); ?>"
                   id="btn-404-kegiatan"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold text-sm hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Lihat Semua Kegiatan
                </a>

                <a href="<?php echo e(url('/dashboard')); ?>"
                   id="btn-404-dashboard"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold text-sm hover:bg-white hover:shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            
            <p class="text-gray-400 text-xs mt-12">
                &copy; <?php echo e(date('Y')); ?> Greennovate. Mari jaga bumi bersama 🌍
            </p>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\Alvin Susanto\Documents\GitHub\SI4705-KELA\SI4705-KELA-main\greennovate\resources\views/errors/404.blade.php ENDPATH**/ ?>