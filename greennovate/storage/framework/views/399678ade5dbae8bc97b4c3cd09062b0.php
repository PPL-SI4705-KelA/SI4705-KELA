<?php $__env->startSection('title', 'Pesan Masuk – Admin Greennovate'); ?>
<?php $__env->startSection('page-title', 'Pesan Masuk'); ?>
<?php $__env->startSection('page-subtitle', 'Kelola percakapan dari pengguna'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Daftar Percakapan</h2>
    </div>

    <?php if($conversations->isEmpty()): ?>
        <div class="p-8 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <p>Belum ada percakapan masuk dari pengguna.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium border-b border-gray-200">Pengguna</th>
                        <th class="px-6 py-4 font-medium border-b border-gray-200">Status</th>
                        <th class="px-6 py-4 font-medium border-b border-gray-200">Pesan Terakhir</th>
                        <th class="px-6 py-4 font-medium border-b border-gray-200 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold relative">
                                        <?php echo e(strtoupper(substr($conv->user->name, 0, 1))); ?>

                                        <?php if($conv->unread_count > 0): ?>
                                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white"></span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 flex items-center gap-2">
                                            <?php echo e($conv->user->name); ?>

                                            <?php if($conv->unread_count > 0): ?>
                                                <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full"><?php echo e($conv->unread_count); ?> Baru</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?php echo e($conv->user->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($conv->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo e(ucfirst($conv->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo e($conv->last_message_at ? $conv->last_message_at->diffForHumans() : '-'); ?>

                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?php echo e(route('admin.chat.show', $conv->id)); ?>" class="inline-flex items-center gap-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-1.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    Buka Pesan
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\egiag\Downloads\Gabungan\greennovate_final\resources\views/admin/chat/index.blade.php ENDPATH**/ ?>