<?php $__env->startSection('title', 'Peserta Kegiatan – Greennovate'); ?>
<?php $__env->startSection('page-title', 'Peserta Kegiatan'); ?>
<?php $__env->startSection('page-subtitle', 'Daftar partisipan aktif di setiap kegiatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6">

    
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col gap-6">
        
        <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-[#0D8B41]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-sm">Filter & Saring Peserta</h3>
                <p class="text-xs text-gray-400">Pilih kegiatan dan status pendaftaran untuk memperbarui daftar di bawah</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div>
                <label for="kegiatan-select" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Pilih Kegiatan
                </label>
                <select id="kegiatan-select"
                        onchange="if (this.value) window.location.href = '/admin/kegiatan/' + this.value + '/peserta' + window.location.search;"
                        class="px-4 w-full h-12 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100">
                    <option value="" disabled <?php echo e(!$selectedKegiatan ? 'selected' : ''); ?>>-- Pilih Kegiatan --</option>
                    <?php $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k->id); ?>" <?php echo e($selectedKegiatan && $selectedKegiatan->id == $k->id ? 'selected' : ''); ?>>
                            <?php echo e($k->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <form method="GET" action="<?php echo e($selectedKegiatan ? route('admin.kegiatan.peserta', $selectedKegiatan->id) : route('admin.peserta.index')); ?>" id="filter-form">
                    <label for="status-filter" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status Pendaftaran
                    </label>
                    <select id="status-filter" name="status" onchange="this.form.submit()"
                            class="px-4 w-full h-12 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100">
                        <option value="">Semua Status</option>
                        <option value="terdaftar" <?php echo e(request('status') == 'terdaftar' ? 'selected' : ''); ?>>Terdaftar</option>
                        <option value="hadir" <?php echo e(request('status') == 'hadir' ? 'selected' : ''); ?>>Hadir</option>
                        <option value="batal" <?php echo e(request('status') == 'batal' ? 'selected' : ''); ?>>Batal</option>
                        <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-sm">
                <?php if($selectedKegiatan): ?>
                    Peserta untuk: <span class="text-[#0D8B41]"><?php echo e($selectedKegiatan->nama); ?></span>
                <?php else: ?>
                    Silakan Pilih Kegiatan Terlebih Dahulu
                <?php endif; ?>
            </h2>
            <span class="text-xs text-gray-400">Total: <?php echo e($selectedKegiatan ? $peserta->total() : 0); ?></span>
        </div>

        <?php if(!$selectedKegiatan): ?>
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <p class="text-sm font-medium">Pilih kegiatan pada dropdown di atas untuk memantau peserta.</p>
            </div>
        <?php elseif($peserta->isEmpty()): ?>
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">Belum ada peserta terdaftar</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID Peserta</th>
                            <th class="px-6 py-4">Nama Pengguna</th>
                            <th class="px-6 py-4">Nama Kegiatan</th>
                            <th class="px-6 py-4">Tanggal Daftar</th>
                            <th class="px-6 py-4">Status Pendaftaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        <?php $__currentLoopData = $peserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-700">#<?php echo e($p->id); ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo e($p->nama_lengkap); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($p->user ? $p->user->email : 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium"><?php echo e($p->kegiatan ? $p->kegiatan->nama : 'N/A'); ?></td>
                            <td class="px-6 py-4 text-gray-500">
                                <?php echo e($p->created_at ? $p->created_at->translatedFormat('d M Y, H:i') : '-'); ?>

                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusLabel = match ($p->status) {
                                        'Terdaftar' => 'Terdaftar',
                                        'Hadir'     => 'Hadir',
                                        'Selesai'   => 'Selesai',
                                        'Dibatalkan'=> 'Batal',
                                        default     => $p->status,
                                    };
                                    $statusColor = match ($p->status) {
                                        'Terdaftar' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Hadir'     => 'bg-green-50 text-green-700 border-green-100',
                                        'Selesai'   => 'bg-gray-50 text-gray-700 border-gray-100',
                                        'Dibatalkan'=> 'bg-red-50 text-red-700 border-red-100',
                                        default     => 'bg-gray-50 text-gray-500 border-gray-100',
                                    };
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border <?php echo e($statusColor); ?>">
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <?php if($peserta->hasPages()): ?>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <?php echo e($peserta->links()); ?>

                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rizky\Documents\GitHub\SI4705-KELA\greennovate\resources\views/admin/monitoring/peserta.blade.php ENDPATH**/ ?>