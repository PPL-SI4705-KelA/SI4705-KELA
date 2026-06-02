<?php $__env->startSection('title', 'Daftar Kegiatan - ' . $kegiatan->nama); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-2xl px-6 mt-4 pb-16">

    
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-green-600 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="<?php echo e(route('kegiatan.index')); ?>" class="hover:text-green-600 transition-colors">Kegiatan</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="<?php echo e(route('kegiatan.show', $kegiatan->slug ?? $kegiatan->id)); ?>" class="hover:text-green-600 transition-colors truncate max-w-xs">
            <?php echo e($kegiatan->nama); ?>

        </a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 font-medium">Pendaftaran</span>
    </nav>

    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Pendaftaran</h1>
        <p class="text-gray-500">Isi data diri Anda untuk mendaftar kegiatan <span class="font-semibold text-gray-700"><?php echo e($kegiatan->nama); ?></span></p>
    </div>

    
    <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-8 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-green-900 text-sm"><?php echo e($kegiatan->nama); ?></p>
            <p class="text-green-700 text-xs">
                <?php echo e($kegiatan->tanggal ? $kegiatan->tanggal->translatedFormat('d F Y') : '-'); ?>

                <?php if($kegiatan->quota > 0): ?>
                    · Sisa kuota: <?php echo e($kegiatan->remaining_quota); ?> peserta
                <?php endif; ?>
            </p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

        <?php if($errors->any()): ?>
            <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 text-sm">
                <ul class="space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="formDaftar" method="POST" action="<?php echo e(route('kegiatan.daftar', $kegiatan->slug ?? $kegiatan->id)); ?>">
            <?php echo csrf_field(); ?>

            
            <div class="mb-5">
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nama_lengkap"
                       name="nama_lengkap"
                       value="<?php echo e(old('nama_lengkap', $user->name ?? '')); ?>"
                       placeholder="Masukkan nama lengkap Anda"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-5">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nomor HP <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="no_hp"
                       name="no_hp"
                       value="<?php echo e(old('no_hp', $user->phone ?? '')); ?>"
                       placeholder="Contoh: 08123456789"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-6">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea id="alamat"
                          name="alamat"
                          rows="3"
                          placeholder="Masukkan alamat lengkap Anda"
                          class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm resize-none <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('alamat')); ?></textarea>
                <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-8">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox"
                           name="pernyataan"
                           value="1"
                           class="mt-0.5 w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500 <?php $__errorArgs = ['pernyataan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <span class="text-sm text-gray-600 leading-relaxed">
                        Saya menyatakan bahwa data yang saya isi adalah benar, dan saya bersedia mengikuti kegiatan ini sesuai dengan ketentuan yang berlaku.
                    </span>
                </label>
                <?php $__errorArgs = ['pernyataan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1 ml-7"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="flex items-center gap-3">
                <button type="button" onclick="openModal()"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 text-sm">
                    Kirim Pendaftaran
                </button>
                <a href="<?php echo e(route('kegiatan.show', $kegiatan->slug ?? $kegiatan->id)); ?>"
                   class="flex-1 text-center border border-gray-200 text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium py-3 px-6 rounded-xl transition-colors duration-200 text-sm">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>

<script>
    function openModal() {
        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function confirmSubmit() {
        const form = document.getElementById('formDaftar');

        if (form) {
            form.requestSubmit(); // 🔥 ini kunci fix logout
        }
    }

    function closeModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    }
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn {
    animation: fadeIn 0.25s ease-out;
}
</style>

<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl animate-fadeIn">

        <h2 class="text-lg font-semibold text-gray-900 mb-3">
            Konfirmasi Pendaftaran
        </h2>

        <p class="text-sm text-gray-600 mb-4">
            Apakah Anda yakin ingin mendaftar untuk kegiatan 
            <span class="font-semibold text-gray-800"><?php echo e($kegiatan->nama); ?></span>?
        </p>

        <div class="text-sm text-gray-600 mb-4">
            <p class="font-medium mb-2">Hal yang perlu dipersiapkan:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Pakaian lapangan yang nyaman & sepatu tertutup</li>
                <li>Topi, tabir surya & perlengkapan anti panas</li>
                <li>Botol minum pribadi (min. 1 liter)</li>
                <li>Sarung tangan kerja / berkebun</li>
                <li>Jas hujan atau ponco (antisipasi cuaca)</li>
                <li>Obat-obatan pribadi & P3K dasar</li>
                <li>Identitas diri (KTP/KTM)</li>
            </ul>
        </div>

        <div class="text-xs text-gray-500 mb-5">
            📍 Lokasi: <?php echo e($kegiatan->lokasiLahan?->nama ?? '-'); ?> <br>
            📅 Tanggal: <?php echo e($kegiatan->tanggal?->translatedFormat('l, d F Y') ?? '-'); ?>

        </div>

        <div class="flex gap-3">
            <button onclick="closeModal()"
                    class="flex-1 border border-gray-200 text-gray-500 py-2.5 rounded-lg">
                Batal
            </button>

            <button type="button" onclick="confirmSubmit()"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg">
                Ya, Saya Yakin
            </button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PPL-SI4705-KelA\greennovate\SI4705-KELA-main\greennovate\resources\views/kegiatan/daftar.blade.php ENDPATH**/ ?>