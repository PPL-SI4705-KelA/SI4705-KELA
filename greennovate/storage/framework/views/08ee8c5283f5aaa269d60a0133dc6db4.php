

<?php $__env->startSection('title', 'Ubah Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-xl px-6 mt-12">

    <?php if(session('success')): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-xl shadow border">
        <h1 class="text-xl font-bold mb-6">Ubah Password</h1>

        <form method="POST" action="<?php echo e(route('profile.password.update')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label>Password Lama</label>
                <input type="password" name="old_password" class="w-full mt-1 border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Password Baru</label>
                <input type="password" name="new_password" class="w-full mt-1 border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Konfirmasi Password</label>
                <input type="password" name="new_password_confirmation" class="w-full mt-1 border rounded p-2">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan Password
            </button>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Documents\GitHub\SI4705-KELA\greennovate\resources\views/change-password.blade.php ENDPATH**/ ?>