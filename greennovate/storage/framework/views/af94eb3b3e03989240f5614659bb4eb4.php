<?php $__env->startSection('title', 'Dashboard Admin – Greennovate'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Ringkasan data sistem Greennovate'); ?>

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

    
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3">
        <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-gray-900"><?php echo e($stats['total_pengguna']); ?></div>
            <div class="text-xs text-gray-400 mt-0.5">Pengguna Terdaftar</div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3">
        <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-gray-900"><?php echo e($stats['total_admin']); ?></div>
            <div class="text-xs text-gray-400 mt-0.5">Admin Aktif</div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-3">
        <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-gray-900"><?php echo e($stats['total_lokasi']); ?></div>
            <div class="text-xs text-gray-400 mt-0.5">Lokasi Lahan Terdaftar</div>
        </div>
    </div>

</div>


<div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-800 text-sm">Pengguna Terbaru</h2>
        <span class="text-xs text-gray-400">5 terbaru</span>
    </div>

    <?php if($stats['pengguna_terbaru']->isEmpty()): ?>
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm">Belum ada pengguna terdaftar</p>
        </div>
    <?php else: ?>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $stats['pengguna_terbaru']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 px-6 py-3.5">
                <div class="w-9 h-9 rounded-full bg-[#0D8B41] flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate"><?php echo e($user->name); ?></p>
                    <p class="text-xs text-gray-400 truncate"><?php echo e($user->email ?? $user->phone); ?></p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="text-xs text-gray-400"><?php echo e($user->created_at->diffForHumans()); ?></span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        <?php echo e($user->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600'); ?>">
                        <?php echo e($user->is_active ? 'Aktif' : 'Nonaktif'); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>


<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="font-bold text-gray-800 text-sm mb-4">Akses Cepat</h2>
    <div class="flex flex-wrap gap-3">
        <a href="<?php echo e(route('admin.lokasi.index')); ?>"
           class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Kelola Lokasi Lahan
        </a>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/awangwahyu/Documents/GitHub/SI4705-KELA/greennovate/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>