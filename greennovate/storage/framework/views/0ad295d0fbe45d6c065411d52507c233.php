

<?php $__env->startSection('title', 'Dashboard - Greennovate'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl px-6 mt-12">

    <?php if(session('success')): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-500 mb-8">Selamat datang kembali, <span class="font-semibold"><?php echo e(Auth::user()->name); ?></span>!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 border border-green-100 p-6 rounded-lg text-center">
                <p class="text-sm text-green-800 font-medium mb-1">Status Akun</p>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-200 text-green-800 text-sm font-semibold">
                    <?php echo e(Auth::user()->is_active ? 'Aktif' : 'Nonaktif'); ?>

                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 p-6 rounded-lg text-center">
                <p class="text-sm text-blue-800 font-medium mb-1">Role Anda</p>
                <h3 class="text-xl font-bold text-blue-900 uppercase"><?php echo e(Auth::user()->role); ?></h3>
            </div>

            <div class="bg-orange-50 border border-orange-100 p-6 rounded-lg text-center">
                <p class="text-sm text-orange-800 font-medium mb-1">Informasi Kontak</p>
                <p class="text-sm text-orange-900 truncate"><?php echo e(Auth::user()->email ?? Auth::user()->phone); ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            
            <a href="<?php echo e(route('chat.index')); ?>" class="flex items-center gap-4 p-5 rounded-xl border border-gray-200 bg-white hover:bg-green-50 transition group">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900">Hubungi Admin</p>
                    <p class="text-sm text-gray-500 italic">Punya pertanyaan? Chat kami di sini.</p>
                </div>
                <?php if(isset($unreadChatCount) && $unreadChatCount > 0): ?>
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?php echo e($unreadChatCount); ?></span>
                <?php endif; ?>
            </a>

            
            <a href="<?php echo e(route('riwayat.index')); ?>" class="flex items-center gap-4 p-5 rounded-xl border border-gray-200 bg-white hover:bg-blue-50 transition group">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900">Riwayat Partisipasi</p>
                    <p class="text-sm text-gray-500 italic">Lihat kontribusi penanaman pohon Anda.</p>
                </div>
            </a>
        </div>

        
        <div class="mt-6">
            <div class="bg-gray-50 border border-gray-200/60 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#0D8B41] flex items-center justify-center text-white shadow-md shadow-green-900/10 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Kontribusi Penanaman Pohon</h3>
                        <p class="text-gray-500 text-sm mt-0.5">Ikut serta menghijaukan lahan bekas tambang dengan mengotomatisasi pemesanan bibit tanaman secara real-time.</p>
                    </div>
                </div>
                <div class="w-full md:w-auto flex-shrink-0">
                    <a href="<?php echo e(route('pembelian.index')); ?>" 
                    class="block w-full text-center bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold px-6 py-3 rounded-full text-sm transition-all shadow-md hover:shadow-lg hover:shadow-green-900/20">
                        Mulai Kontribusi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\egiag\Downloads\Gabungan\greennovate_final\resources\views/user/dashboard.blade.php ENDPATH**/ ?>