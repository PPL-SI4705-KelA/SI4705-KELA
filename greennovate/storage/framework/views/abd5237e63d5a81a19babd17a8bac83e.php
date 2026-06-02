

<?php $__env->startSection('title', 'Daftar Pengguna – Greennovate'); ?>
<?php $__env->startSection('page-title', 'Daftar Pengguna'); ?>
<?php $__env->startSection('page-subtitle', 'Monitoring seluruh akun terdaftar beserta perannya di sistem'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col gap-6">

    
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <form method="GET" action="<?php echo e(route('admin.pengguna.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end" id="filter-form">
            
            <div>
                <label for="search-input" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Cari Pengguna</label>
                <div style="position: relative;">
                    <div style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; padding-left: 14px; pointer-events: none;" class="text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="search-input" name="search" value="<?php echo e(request('search')); ?>"
                           placeholder="Nama, email, atau no HP..."
                           class="w-full h-12 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100 outline-none"
                           style="padding-left: 44px;">
                </div>
            </div>

            
            <div>
                <label for="role-filter" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Role / Peran</label>
                <select id="role-filter" name="role" onchange="this.form.submit()"
                        class="w-full h-12 px-4 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100">
                    <option value="">Semua Role</option>
                    <option value="user" <?php echo e(request('role') == 'user' ? 'selected' : ''); ?>>User (Pengguna Biasa)</option>
                    <option value="petugas" <?php echo e(request('role') == 'petugas' ? 'selected' : ''); ?>>Petugas</option>
                    <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin (Administrator)</option>
                </select>
            </div>

            
            <div>
                <label for="status-filter" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Status Akun</label>
                <select id="status-filter" name="status" onchange="this.form.submit()"
                        class="w-full h-12 px-4 rounded-xl border-2 border-gray-300 text-sm font-bold focus:border-[#0D8B41] focus:ring focus:ring-green-100">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(request('status') == 'nonaktif' ? 'selected' : ''); ?>>Nonaktif</option>
                </select>
            </div>

            
            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 h-12 inline-flex items-center justify-center bg-[#0D8B41] hover:bg-[#085c2b] text-white text-sm font-bold px-4 rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-md">
                    Cari
                </button>
                <?php if(request()->anyFilled(['search', 'role', 'status'])): ?>
                    <a href="<?php echo e(route('admin.pengguna.index')); ?>"
                       class="h-12 inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold px-4 rounded-xl transition-all">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-sm">Daftar Pengguna Sistem</h2>
            <span class="text-xs text-gray-400">Total: <?php echo e($penggunas->total()); ?></span>
        </div>

        <?php if($penggunas->isEmpty()): ?>
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">Belum ada data pengguna</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID User</th>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Email / No HP</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status Akun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        <?php $__currentLoopData = $penggunas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-700">#<?php echo e($p->id); ?></td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($p->name); ?></td>
                            <td class="px-6 py-4 text-gray-600">
                                <div class="font-medium"><?php echo e($p->email ?? '-'); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($p->phone ?? '-'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $roleColor = match ($p->role) {
                                        'admin'   => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'petugas' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        default   => 'bg-blue-50 text-blue-700 border-blue-100',
                                    };
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border <?php echo e($roleColor); ?>">
                                    <?php echo e(ucfirst($p->role)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border
                                    <?php echo e($p->is_active ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100'); ?>">
                                    <?php echo e($p->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <?php if($penggunas->hasPages()): ?>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    <?php echo e($penggunas->links()); ?>

                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\egiag\Downloads\Gabungan\greennovate_final\resources\views/admin/monitoring/pengguna.blade.php ENDPATH**/ ?>