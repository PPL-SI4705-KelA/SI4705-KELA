<?php $__env->startSection('title', 'Tambah Jenis Pohon - Greennovate Admin'); ?>
<?php $__env->startSection('page-title', 'Tambah Jenis Pohon'); ?>
<?php $__env->startSection('page-subtitle', 'Tambahkan data jenis pohon dan harga baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-2xl">

    
    <div class="mb-6">
        <a href="<?php echo e(route('admin.jenis-pohon.index')); ?>"
           class="text-sm text-gray-400 hover:text-gray-600 mb-2 inline-block">
            ← Kembali ke Daftar Jenis Pohon
        </a>
        <h2 class="text-xl font-bold text-gray-900">Tambah Jenis Pohon</h2>
        <p class="text-sm text-gray-500 mt-1">Isi form di bawah untuk menambahkan jenis pohon baru.</p>
    </div>

    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="<?php echo e(route('admin.jenis-pohon.store')); ?>"
              id="form-create-pohon"
              class="flex flex-col gap-5">
            <?php echo csrf_field(); ?>

            <?php echo $__env->make('admin.jenis-pohon._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="<?php echo e(route('admin.jenis-pohon.index')); ?>"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        id="btn-simpan"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PPL-SI4705-KelA\greennovate\SI4705-KELA-main\greennovate\resources\views/admin/jenis-pohon/create.blade.php ENDPATH**/ ?>