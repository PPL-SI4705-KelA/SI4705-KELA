<?php $__env->startSection('title', 'Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl px-6 mt-12">

    
    <?php if(session('success')): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow border">

        
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 rounded-full bg-green-600 flex items-center justify-center text-white text-xl font-bold">
                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

            </div>

            <div>
                <h1 class="text-2xl font-bold">Profil Saya</h1>
                <p class="text-gray-500 text-sm">Informasi akun Anda</p>
            </div>
        </div>

        
        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">Nama</p>
                <p class="font-semibold"><?php echo e(Auth::user()->name); ?></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-semibold"><?php echo e(Auth::user()->email); ?></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nomor HP</p>
                <p class="font-semibold"><?php echo e(Auth::user()->phone ?? '-'); ?></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Alamat</p>
                <p class="font-semibold"><?php echo e(Auth::user()->city ?? '-'); ?></p>
            </div>

        </div>

        
        <div class="mt-6">
            <p class="text-sm text-gray-500">Role</p>
            <p class="font-semibold uppercase"><?php echo e(Auth::user()->role); ?></p>
        </div>

        
        <div class="flex flex-wrap gap-4 mt-8">

            
            <a href="<?php echo e(route('profile.edit')); ?>"
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Edit Profil
            </a>

            
            <a href="<?php echo e(route('profile.password.form')); ?>"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Ubah Password
            </a>

        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/awangwahyu/Documents/GitHub/SI4705-KELA/greennovate/resources/views/profile/index.blade.php ENDPATH**/ ?>