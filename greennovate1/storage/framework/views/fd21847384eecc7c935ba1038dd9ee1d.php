<?php $__env->startSection('title', 'Daftar Kegiatan - Greennovate'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-6xl mx-auto px-6 mt-4 pb-16">

    
    <div class="mb-10 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold tracking-widest uppercase bg-green-100 text-green-700 mb-4">
            🌿 Program Lingkungan
        </span>
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Daftar Kegiatan</h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto">
            Temukan kegiatan penghijauan dan lingkungan yang sesuai dengan minat Anda.
            Bergabunglah dan berkontribusi untuk bumi yang lebih hijau.
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

    
    <form method="GET" action="<?php echo e(route('kegiatan.index')); ?>"
          class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Lokasi</label>
                <select name="lokasi"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Lokasi</option>
                    <?php $__currentLoopData = $lokasiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($lokasi->id); ?>"
                                <?php echo e(request('lokasi') == $lokasi->id ? 'selected' : ''); ?>>
                            <?php echo e($lokasi->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Status</label>
                <select name="status"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = ['Persiapan', 'Berlangsung', 'Selesai', 'Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Bulan</label>
                <select name="bulan"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Bulan</option>
                    <?php $__currentLoopData = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($i + 1); ?>" <?php echo e(request('bulan') == $i + 1 ? 'selected' : ''); ?>><?php echo e($bln); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4
                               rounded-lg text-sm transition-colors duration-200">
                    Cari
                </button>
                <?php if(request()->hasAny(['lokasi', 'status', 'bulan', 'tahun'])): ?>
                    <a href="<?php echo e(route('kegiatan.index')); ?>"
                       class="flex-1 text-center border border-gray-200 text-gray-500 hover:text-gray-700
                              font-medium py-2.5 px-4 rounded-lg text-sm transition-colors duration-200">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    
    <?php if($kegiatan->total() > 0): ?>
        <p class="text-sm text-gray-400 mb-5">
            Menampilkan <span class="font-semibold text-gray-600"><?php echo e($kegiatan->firstItem()); ?>–<?php echo e($kegiatan->lastItem()); ?></span>
            dari <span class="font-semibold text-gray-600"><?php echo e($kegiatan->total()); ?></span> kegiatan
        </p>
    <?php endif; ?>

    
    <?php if($kegiatan->isEmpty()): ?>
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Belum ada kegiatan</h2>
            <p class="text-gray-400 text-sm mb-4">
                <?php if(request()->hasAny(['lokasi', 'status', 'bulan'])): ?>
                    Tidak ada kegiatan yang sesuai dengan filter yang dipilih.
                <?php else: ?>
                    Kegiatan akan segera hadir. Pantau terus halaman ini.
                <?php endif; ?>
            </p>
            <?php if(request()->hasAny(['lokasi', 'status', 'bulan'])): ?>
                <a href="<?php echo e(route('kegiatan.index')); ?>"
                   class="text-green-600 hover:underline text-sm font-medium">
                    Lihat semua kegiatan →
                </a>
            <?php endif; ?>
        </div>

    <?php else: ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('kegiatan.show', $item->slug ?? $item->id)); ?>"
                   class="group block bg-white rounded-2xl border border-gray-100 shadow-sm
                          hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    
                    <div class="relative h-44 overflow-hidden bg-gradient-to-br from-green-400 to-emerald-600">
                        <?php if($item->image): ?>
                            <img src="<?php echo e(asset('storage/' . $item->image)); ?>"
                                 alt="<?php echo e($item->nama); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

                        
                        <?php if($item->quota > 0): ?>
                            <div class="absolute top-3 right-3">
                                <?php $sisa = $item->remaining_quota; ?>
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold shadow
                                    <?php echo e($sisa === 0 ? 'bg-red-100 text-red-600' : 'bg-white/95 text-gray-700'); ?>">
                                    <?php if($sisa === 0): ?>
                                        Penuh
                                    <?php else: ?>
                                        Sisa <?php echo e($sisa); ?>

                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-900 text-base mb-3 line-clamp-2 group-hover:text-green-700 transition-colors">
                            <?php echo e($item->nama); ?>

                        </h3>

                        
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1.5">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate"><?php echo e($item->lokasiLahan?->nama ?? '-'); ?></span>
                        </div>

                        
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e($item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') : '-'); ?>

                        </div>

                        
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Target: <span class="font-medium text-gray-700"><?php echo e(number_format($item->target_pohon)); ?> pohon</span>
                        </div>

                        
                        <?php if($item->quota > 0): ?>
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Kuota Peserta</span>
                                    <span class="font-medium"><?php echo e($item->registered_count); ?>/<?php echo e($item->quota); ?></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all duration-500
                                        <?php echo e($item->progressPercentage() >= 100 ? 'bg-red-400' : 'bg-green-500'); ?>"
                                         style="width: <?php echo e($item->progressPercentage()); ?>%"></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">
                                <?php echo e($item->realisasi_pohon); ?>/<?php echo e($item->target_pohon); ?> pohon ditanam
                            </span>
                            <span class="text-xs font-semibold text-green-700 group-hover:underline">
                                Lihat Detail →
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($kegiatan->hasPages()): ?>
            <div class="mt-10 flex justify-center">
                <?php echo e($kegiatan->links()); ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PPL-SI4705-KelA\greennovate\SI4705-KELA-main\greennovate\resources\views/kegiatan/index.blade.php ENDPATH**/ ?>