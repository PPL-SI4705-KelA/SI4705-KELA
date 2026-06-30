<?php $__env->startSection('title', __('Riwayat Partisipasi') . ' - Greennovate'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl px-6 mt-6 mb-16">

    
    <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-700 transition mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <?php echo e(__('Back to Dashboard')); ?>

    </a>

    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900"><?php echo e(__('Riwayat Partisipasi')); ?></h1>
        <p class="text-gray-500 mt-1"><?php echo e(__('Lihat seluruh riwayat kegiatan, donasi, dan pembelian Anda')); ?></p>
    </div>

    
    <?php if(session('error')): ?>
        <div id="error-toast" class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 text-red-700 border border-red-200 shadow-sm animate-slide-in">
            <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="font-medium text-sm"><?php echo e(session('error')); ?></span>
            <button onclick="document.getElementById('error-toast').remove()" class="ml-auto text-red-400 hover:text-red-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    <?php endif; ?>

    
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-1.5">
        <nav class="flex gap-1">
            <a href="<?php echo e(route('riwayat.index')); ?>"
               class="flex-1 text-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all
               <?php echo e(!$activeType ? 'bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'); ?>">
                <?php echo e(__('Semua')); ?>

            </a>
            <a href="<?php echo e(route('riwayat.index', ['type' => 'kegiatan'])); ?>"
               class="flex-1 text-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all
               <?php echo e($activeType === 'kegiatan' ? 'bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'); ?>">
                <span class="hidden sm:inline">🌱 </span><?php echo e(__('Kegiatan')); ?>

            </a>
            <a href="<?php echo e(route('riwayat.index', ['type' => 'donasi'])); ?>"
               class="flex-1 text-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all
               <?php echo e($activeType === 'donasi' ? 'bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'); ?>">
                <span class="hidden sm:inline">💚 </span><?php echo e(__('Donasi')); ?>

            </a>
            <a href="<?php echo e(route('riwayat.index', ['type' => 'pembelian'])); ?>"
               class="flex-1 text-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all
               <?php echo e($activeType === 'pembelian' ? 'bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'); ?>">
                <span class="hidden sm:inline">🛒 </span><?php echo e(__('Pembelian')); ?>

            </a>
        </nav>
    </div>

    
    <?php if($items->count() > 0): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('riwayat.detail', ['type' => $entry['type'], 'id' => $entry['id']])); ?>"
                   class="block bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-gray-300 transition-all group">
                    <div class="flex items-center gap-4">
                        
                        <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center
                            <?php if($entry['type'] === 'kegiatan'): ?>
                                bg-green-100 text-green-600
                            <?php elseif($entry['type'] === 'donasi'): ?>
                                bg-blue-100 text-blue-600
                            <?php else: ?>
                                bg-amber-100 text-amber-600
                            <?php endif; ?>
                        ">
                            <?php if($entry['type'] === 'kegiatan'): ?>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            <?php elseif($entry['type'] === 'donasi'): ?>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            <?php endif; ?>
                        </div>

                        
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm truncate"><?php echo e($entry['nama']); ?></p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400"><?php echo e($entry['tanggal']->format('d M Y')); ?></span>
                                <span class="text-gray-300">·</span>
                                <span class="text-xs capitalize text-gray-400">
                                    <?php if($entry['type'] === 'kegiatan'): ?> <?php echo e(__('Kegiatan')); ?>

                                    <?php elseif($entry['type'] === 'donasi'): ?> <?php echo e(__('Donasi')); ?>

                                    <?php else: ?> <?php echo e(__('Pembelian')); ?>

                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                        
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <?php
                                $colorMap = [
                                    'green'  => 'bg-green-100 text-green-700',
                                    'yellow' => 'bg-yellow-100 text-yellow-700',
                                    'red'    => 'bg-red-100 text-red-700',
                                    'blue'   => 'bg-blue-100 text-blue-700',
                                    'gray'   => 'bg-gray-100 text-gray-600',
                                ];
                                $badgeClass = $colorMap[$entry['status']['color']] ?? $colorMap['gray'];
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($badgeClass); ?>">
                                <?php echo e($entry['status']['label']); ?>

                            </span>

                            
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($items->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($items->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1"><?php echo e(__('Belum ada riwayat partisipasi')); ?></h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto">
                <?php echo e(__('Mulai ikuti kegiatan tanam pohon, berdonasi, atau beli bibit untuk melihat riwayat Anda di sini.')); ?>

            </p>
            <a href="<?php echo e(route('kegiatan.index')); ?>" class="inline-flex items-center gap-2 mt-6 bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white font-medium px-5 py-2.5 rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-sm hover:shadow-md text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <?php echo e(__('Jelajahi Kegiatan')); ?>

            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.4s ease-out;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss error toast after 5 seconds
        const toast = document.getElementById('error-toast');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'opacity 0.5s, transform 0.5s';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/awangwahyu/Documents/GitHub/SI4705-KELA/greennovate/resources/views/riwayat/index.blade.php ENDPATH**/ ?>