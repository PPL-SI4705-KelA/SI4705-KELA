<?php $__env->startSection('title', 'Semua Kegiatan - Greennovate'); ?>
<?php $__env->startSection('header', 'Semua Kegiatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl" x-data="allActivities()">

    
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-1">Semua Kegiatan</h2>
        <p class="text-sm text-gray-500">Daftar lengkap kegiatan yang ditugaskan kepada Anda</p>
    </div>

    
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Cari Kegiatan</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama kegiatan..."
                           x-model="searchQuery" @input.debounce.300ms="filterActivities()"
                           class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Status</label>
                <select x-model="statusFilter" @change="filterActivities()"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Status</option>
                    <option value="Berlangsung">Berlangsung</option>
                    <option value="Persiapan">Akan Datang</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Lokasi</label>
                <select x-model="lokasiFilter" @change="filterActivities()"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">Semua Lokasi</option>
                    <?php $__currentLoopData = $lokasiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($lokasi->id); ?>"><?php echo e($lokasi->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="flex items-end gap-2">
                <button @click="resetFilters()" x-show="hasActiveFilters()"
                        class="flex-1 text-center border border-gray-200 text-gray-500 hover:text-gray-700 font-medium py-2.5 px-4 rounded-lg text-sm transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    
    <?php if($kegiatan->total() > 0): ?>
        <p class="text-sm text-gray-400 mb-4">
            Menampilkan <span class="font-semibold text-gray-600"><?php echo e($kegiatan->firstItem()); ?>–<?php echo e($kegiatan->lastItem()); ?></span>
            dari <span class="font-semibold text-gray-600"><?php echo e($kegiatan->total()); ?></span> kegiatan
        </p>
    <?php endif; ?>

    
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Kegiatan</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Lokasi</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[180px]">Progress</th>
                        <th class="text-center px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $pct = $item->target_pohon > 0 ? min(round(($item->realisasi_pohon / $item->target_pohon) * 100), 100) : 0;
                            $pColor = $pct < 50 ? 'bg-orange-500' : ($pct < 75 ? 'bg-yellow-500' : 'bg-green-600');
                            $statusBadge = match($item->status) {
                                'Berlangsung' => 'bg-green-50 text-green-700',
                                'Persiapan'   => 'bg-blue-50 text-blue-600',
                                'Selesai'     => 'bg-gray-100 text-gray-500',
                                'Dibatalkan'  => 'bg-red-50 text-red-600',
                                default       => 'bg-gray-100 text-gray-500',
                            };
                            $statusLabel = match($item->status) {
                                'Persiapan' => 'Akan Datang',
                                default     => $item->status,
                            };
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 mb-0.5"><?php echo e(Str::limit($item->nama, 40)); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y')); ?></div>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?php echo e($item->lokasiLahan->alamat ?? $item->lokasiLahan->nama ?? '-'); ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($statusBadge); ?>">
                                    <?php if($item->status === 'Berlangsung'): ?>
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    <?php endif; ?>
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="h-2 rounded-full transition-all duration-500 <?php echo e($pColor); ?>" style="width: <?php echo e($pct); ?>%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600 whitespace-nowrap"><?php echo e($pct); ?>%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?php echo e(route('petugas.dashboard')); ?>"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1a8245] hover:text-green-800 transition-colors">
                                    Detail
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-500 mb-1">Tidak ada kegiatan ditemukan</h3>
                                <p class="text-xs text-gray-400">Coba ubah filter pencarian Anda</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if($kegiatan->hasPages()): ?>
        <div class="flex justify-center">
            <?php echo e($kegiatan->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function allActivities() {
    return {
        searchQuery: '<?php echo e(request("search", "")); ?>',
        statusFilter: '<?php echo e(request("status", "")); ?>',
        lokasiFilter: '<?php echo e(request("lokasi", "")); ?>',

        hasActiveFilters() {
            return this.searchQuery || this.statusFilter || this.lokasiFilter;
        },

        filterActivities() {
            const params = new URLSearchParams();
            if (this.searchQuery) params.set('search', this.searchQuery);
            if (this.statusFilter) params.set('status', this.statusFilter);
            if (this.lokasiFilter) params.set('lokasi', this.lokasiFilter);

            window.location.href = '<?php echo e(route("petugas.kegiatan.index")); ?>?' + params.toString();
        },

        resetFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.lokasiFilter = '';
            window.location.href = '<?php echo e(route("petugas.kegiatan.index")); ?>';
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PPL-SI4705-KelA\greennovate\SI4705-KELA-main\greennovate\resources\views/petugas/all-activities.blade.php ENDPATH**/ ?>