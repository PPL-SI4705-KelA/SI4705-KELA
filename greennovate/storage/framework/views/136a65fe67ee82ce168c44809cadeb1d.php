

<?php $__env->startSection('title', 'Daftar Kegiatan - Greennovate'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-5xl px-6 mt-4 pb-16">

    
    <div class="mb-10 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold tracking-widest uppercase bg-green-100 text-green-700 mb-4">
            🌿 Program Lingkungan
        </span>
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Daftar Kegiatan</h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto">
            Temukan kegiatan penghijauan dan lingkungan yang sesuai dengan minat Anda. Bergabunglah dan berkontribusi untuk bumi yang lebih hijau.
        </p>
    </div>

    
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 font-medium">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <?php if($kegiatan->isEmpty()): ?>
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Belum ada kegiatan</h2>
            <p class="text-gray-400 text-sm">Kegiatan akan segera hadir. Pantau terus halaman ini.</p>
        </div>

    <?php else: ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('kegiatan.show', $item->slug ?? $item->id)); ?>"
                   class="group block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    
                    <div class="relative h-44 overflow-hidden bg-gradient-to-br from-green-400 to-emerald-600">
                        <?php if($item->image): ?>
                            <img src="<?php echo e(asset('storage/' . $item->image)); ?>"
                                 alt="<?php echo e($item->nama); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20C19 20 22 3 22 3c-1 2-8 2-8 2 4-2 4-6 4-6-2 0-4 2-4 4 0 0-3-7-8-7C6 7 4 9 4 9c4 0 4-2 4-2 0 0-6 3-6 7.5C2 18 4 20 6 20c2.5 0 4-2 4-2-1 3-5 5-5 5s4 0 7-4.5l1 3H14v-2.5c2.5-1 5-4 5-4-1.5 1-3 1-3 1"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                                <svg class="w-12 h-12 text-white mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        
                        <div class="absolute top-3 left-3">
                            <?php
                                $badgeColor = match($item->status) {
                                    'Berlangsung' => 'bg-white/95 text-green-700',
                                    'Persiapan'   => 'bg-white/95 text-yellow-700',
                                    'Selesai'     => 'bg-white/95 text-gray-600',
                                    'Dibatalkan'  => 'bg-white/95 text-red-600',
                                    default       => 'bg-white/95 text-gray-600',
                                };
                            ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shadow <?php echo e($badgeColor); ?>">
                                <?php if($item->status === 'Berlangsung'): ?>
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                                <?php endif; ?>
                                <?php echo e($item->status); ?>

                            </span>
                        </div>
                    </div>

                    
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-900 text-base mb-2 line-clamp-2 group-hover:text-green-700 transition-colors">
                            <?php echo e($item->nama); ?>

                        </h3>

                        
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e($item->tanggal ? $item->tanggal->translatedFormat('d F Y') : '-'); ?>

                        </div>

                        
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Target: <span class="font-medium text-gray-700"><?php echo e(number_format($item->target_pohon)); ?> pohon</span>
                        </div>

                        
                        <?php if($item->target_pohon > 0): ?>
                            <?php $persen = min(100, round(($item->realisasi_pohon / $item->target_pohon) * 100)); ?>
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Realisasi</span>
                                    <span><?php echo e($persen); ?>%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-green-500 h-1.5 rounded-full transition-all duration-500"
                                         style="width: <?php echo e($persen); ?>%"></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">
                                <?php echo e($item->realisasi_pohon); ?>/<?php echo e($item->target_pohon); ?> pohon
                            </span>
                            <span class="text-xs font-semibold text-green-700 group-hover:underline">
                                Lihat Detail →
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Documents\GitHub\SI4705-KELA\greennovate\resources\views/kegiatan/index.blade.php ENDPATH**/ ?>