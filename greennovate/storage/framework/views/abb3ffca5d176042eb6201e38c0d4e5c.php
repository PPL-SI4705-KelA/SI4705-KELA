

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
                <p class="text-gray-500 text-sm">Kelola informasi akun Anda</p>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('profile.update')); ?>">
            <?php echo csrf_field(); ?>

            <div class="grid md:grid-cols-2 gap-6">

                
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" name="name" value="<?php echo e(Auth::user()->name); ?>"
                        class="w-full mt-1 border rounded-lg p-2 focus:ring focus:ring-green-200">
                </div>

                
                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" name="email" value="<?php echo e(Auth::user()->email); ?>"
                        class="w-full mt-1 border rounded-lg p-2 focus:ring focus:ring-green-200">
                </div>

                
                <div>
                    <label class="text-sm font-medium">Nomor HP</label>
                    <input type="text" name="phone" value="<?php echo e(Auth::user()->phone); ?>"
                        class="w-full mt-1 border rounded-lg p-2 focus:ring focus:ring-green-200"
                        placeholder="08xxxxxxxxxx">
                </div>

                
                <div>
                    <label class="text-sm font-medium">Kota</label>
                    <input type="text" name="city" value="<?php echo e(Auth::user()->city); ?>"
                        class="w-full mt-1 border rounded-lg p-2 focus:ring focus:ring-green-200"
                        placeholder="Contoh: Malang">
                </div>

            </div>

            
            <div class="mt-6">
                <label class="text-sm font-medium">Bio</label>
                <textarea name="bio" rows="3"
                    class="w-full mt-1 border rounded-lg p-2 focus:ring focus:ring-green-200"
                    placeholder="Ceritakan sedikit tentang Anda..."><?php echo e(Auth::user()->bio); ?></textarea>
            </div>

            
            <div class="mt-6">
                <label class="text-sm font-medium">Role</label>
                <input type="text" value="<?php echo e(Auth::user()->role); ?>" disabled
                    class="w-full mt-1 border rounded-lg p-2 bg-gray-100 text-gray-500">
            </div>

            
            <div class="flex justify-between items-center mt-8">

                <button class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                    Simpan Perubahan
                </button>

                <a href="<?php echo e(route('profile.password.form')); ?>"
                   class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                    Ubah Password
                </a>

            </div>

        </form>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Documents\GitHub\SI4705-KELA\greennovate\resources\views/profile.blade.php ENDPATH**/ ?>