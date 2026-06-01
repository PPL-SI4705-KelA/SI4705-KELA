<?php $__env->startSection('title', 'Kelola Kegiatan - Greennovate Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-6xl px-6 mt-8 mb-16">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Kegiatan</h1>
            <p class="text-sm text-gray-500 mt-1">Manajemen kegiatan penghijauan Greennovate</p>
        </div>
        <a href="<?php echo e(route('admin.kegiatan.create')); ?>"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kegiatan
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-4">
        <form method="GET" action="<?php echo e(route('admin.kegiatan.index')); ?>"
              class="flex flex-wrap gap-3 p-4 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari nama kegiatan</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Contoh: Penanaman..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = ['Persiapan', 'Berlangsung', 'Selesai', 'Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-900 transition">Filter</button>
            <?php if(request()->hasAny(['search','status'])): ?>
                <a href="<?php echo e(route('admin.kegiatan.index')); ?>" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800 underline">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Kegiatan</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Petugas</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Target Pohon</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Realisasi</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($kegiatan->nama); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($kegiatan->petugas?->name ?? '-'); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($kegiatan->tanggal?->format('d M Y')); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e(number_format($kegiatan->target_pohon)); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e(number_format($kegiatan->realisasi_pohon)); ?></td>
                        <td class="px-4 py-3">
                            <?php
                                $badge = match($kegiatan->status) {
                                    'Berlangsung' => 'bg-green-100 text-green-700',
                                    'Persiapan'   => 'bg-yellow-100 text-yellow-700',
                                    'Selesai'     => 'bg-blue-100 text-blue-700',
                                    'Dibatalkan'  => 'bg-red-100 text-red-600',
                                    default       => 'bg-gray-100 text-gray-500',
                                };
                            ?>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold <?php echo e($badge); ?>">
                                <?php echo e($kegiatan->status); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.kegiatan.edit', $kegiatan)); ?>"
                                   class="text-xs font-medium text-blue-600 hover:underline">Edit</a>
                                <form method="POST"
                                      action="<?php echo e(route('admin.kegiatan.destroy', $kegiatan)); ?>"
                                      onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-xs font-medium text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Belum ada kegiatan.
                            <a href="<?php echo e(route('admin.kegiatan.create')); ?>" class="text-green-600 hover:underline ml-1">Tambah sekarang</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($kegiatans->hasPages()): ?>
            <div class="px-4 py-3 border-t border-gray-100"><?php echo e($kegiatans->links()); ?></div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-sm text-gray-400 hover:text-gray-600">← Kembali ke Dashboard</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Documents\GitHub\SI4705-KELA\greennovate\resources\views/admin/kegiatan/index.blade.php ENDPATH**/ ?>