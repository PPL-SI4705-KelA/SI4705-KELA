<?php $__env->startSection('title', 'Daftar Pembelian – Greennovate'); ?>
<?php $__env->startSection('page-title', 'Daftar Pembelian'); ?>
<?php $__env->startSection('page-subtitle', 'Monitoring transaksi pembelian dan langganan pohon'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6">

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-sm">Daftar Transaksi Pembelian Pohon</h2>
            <form method="GET" action="<?php echo e(route('admin.pembelian.index')); ?>" id="filter-form" style="width: 100%; max-width: 176px;">
                <select id="status-filter" name="status" onchange="this.form.submit()"
                        class="w-full h-11 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                    <option value="success" <?php echo e(request('status') == 'success' || request('status') == 'sukses' ? 'selected' : ''); ?>>Berhasil</option>
                    <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Kadaluarsa</option>
                    <option value="gagal" <?php echo e(request('status') == 'gagal' ? 'selected' : ''); ?>>Gagal</option>
                </select>
            </form>
        </div>

        <?php if($pembelians->isEmpty()): ?>
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">Belum ada data pembelian</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID Transaksi</th>
                            <th class="px-6 py-4">Nama Pengguna</th>
                            <th class="px-6 py-4">Detail Item</th>
                            <th class="px-6 py-4">Total Harga</th>
                            <th class="px-6 py-4">Tanggal Transaksi</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        <?php $__currentLoopData = $pembelians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-700">#<?php echo e($p->id); ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo e($p->user ? $p->user->name : 'N/A'); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($p->user ? $p->user->email : 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo e($p->nama_item); ?></div>
                                <div class="text-xs text-gray-400">Jumlah: <?php echo e($p->jumlah_item); ?> pcs</div>
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-bold">
                                Rp <?php echo e(number_format($p->total_harga, 0, ',', '.')); ?>

                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                <?php echo e($p->created_at ? $p->created_at->translatedFormat('d M Y, H:i') : '-'); ?>

                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusLabel = match ($p->status) {
                                        'Pending' => 'Menunggu',
                                        'Sukses'  => 'Berhasil',
                                        'Expired' => 'Kadaluarsa',
                                        'Gagal'   => 'Gagal',
                                        default   => $p->status,
                                    };
                                    $statusColor = match ($p->status) {
                                        'Pending' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'Sukses'  => 'bg-green-50 text-green-700 border-green-100',
                                        'Expired' => 'bg-gray-50 text-gray-700 border-gray-100',
                                        'Gagal'   => 'bg-red-50 text-red-700 border-red-100',
                                        default   => 'bg-gray-50 text-gray-500 border-gray-100',
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

            
            <?php if($pembelians->hasPages()): ?>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <?php echo e($pembelians->links()); ?>

                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rizky\Documents\GitHub\SI4705-KELA\greennovate\resources\views/admin/monitoring/pembelian.blade.php ENDPATH**/ ?>