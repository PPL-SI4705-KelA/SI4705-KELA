 

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 pt-24 pb-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-extrabold text-green-800 mb-2">Donasi Penghijauan</h1>
        <p class="text-gray-600 mb-6">Dukung aksi penanaman pohon demi masa depan bumi yang lebih hijau.</p>

        <!-- Alert Error dari Validasi Ganda / Session -->
        <?php if(session('error')): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                <p class="text-sm font-medium text-green-800">
                    <?php echo e(session('success')); ?>

                </p>
            </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm">
                <p class="text-sm font-medium text-yellow-800">
                    <?php echo e(session('warning')); ?>

                </p>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 md:p-8">
            <form action="<?php echo e(route('donations.proses')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <!-- 1. Pilih Kegiatan Aktif -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Pilih Program Kegiatan *</label>
                    <select name="kegiatan_id" class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition <?php $__errorArgs = ['kegiatan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Pilih Kegiatan Penghijauan --</option>
                        <?php $__currentLoopData = $daftarKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kegiatan->id); ?>" <?php echo e(old('kegiatan_id') == $kegiatan->id ? 'selected' : ''); ?>>
                                <?php echo e($kegiatan->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['kegiatan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                        <p class="text-red-500 text-xs mt-1 font-medium"><?php echo e($message); ?></p> 
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 2. Nama Donatur -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Donatur *</label>
                    <input type="text" name="nama_donatur" value="<?php echo e(old('nama_donatur', auth()->user()->name)); ?>" 
                           class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition <?php $__errorArgs = ['nama_donatur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           placeholder="Masukkan nama donatur (misal: Hamba Allah / Nama Lengkap)">
                    <?php $__errorArgs = ['nama_donatur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                        <p class="text-red-500 text-xs mt-1 font-medium"><?php echo e($message); ?></p> 
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 3. Nomor HP -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nomor HP *</label>
                    <input type="text" name="nomor_hp" value="<?php echo e(old('nomor_hp')); ?>" 
                           class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition <?php $__errorArgs = ['nomor_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           placeholder="Contoh: 08123456789">
                    <?php $__errorArgs = ['nomor_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                        <p class="text-red-500 text-xs mt-1 font-medium"><?php echo e($message); ?></p> 
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 4. Nominal Donasi -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nominal Donasi (Rp) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                        <input type="number" name="jumlah" value="<?php echo e(old('jumlah')); ?>" 
                               class="w-full pl-11 pr-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="Minimal 10.000">
                    </div>
                    <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                        <p class="text-red-500 text-xs mt-1 font-medium"><?php echo e($message); ?></p> 
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 5. Catatan Opsional -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Catatan atau Harapan (Opsional)</label>
                    <textarea name="catatan" rows="3" class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Tuliskan doa atau pesan untuk program penanaman ini..."><?php echo e(old('catatan')); ?></textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    Lanjutkan Konfirmasi
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PPL-SI4705-KelA\greennovate\SI4705-KELA-main\greennovate\resources\views/donations/index.blade.php ENDPATH**/ ?>