

<?php $__env->startSection('title', 'Input Realisasi Penanaman - Greennovate'); ?>
<?php $__env->startSection('header', 'Input Realisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-xl mx-auto">
    
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6" aria-label="Breadcrumb">
        <a href="<?php echo e(route('petugas.dashboard')); ?>" class="hover:text-[#1a8245] transition-colors font-medium">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 font-medium">Input Realisasi</span>
    </nav>

    
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        
        <div class="bg-gradient-to-r from-[#1a8245] to-[#15803d] p-5 text-white">
            <h2 class="font-bold text-lg">Catat Realisasi Penanaman</h2>
            <p class="text-green-100 text-xs mt-1">Isi detail jumlah pohon yang berhasil ditanam secara akurat</p>
        </div>

        
        <form action="<?php echo e(route('petugas.realisasi.store')); ?>" method="POST" id="realisasiForm" class="p-6 space-y-5">
            <?php echo csrf_field(); ?>

            
            <div>
                <label for="kegiatan_id" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Pilih Kegiatan <span class="text-red-400">*</span></label>
                <select name="kegiatan_id" id="kegiatan_id" required
                        class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                    <option value="">-- Pilih Kegiatan --</option>
                    <?php $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($keg->id); ?>" <?php echo e((old('kegiatan_id') == $keg->id || $selectedKegiatanId == $keg->id) ? 'selected' : ''); ?>>
                            <?php echo e($keg->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['kegiatan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div id="kegiatanInfoBox" class="hidden bg-gray-50 border border-gray-100 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400 font-medium">Lokasi:</span>
                    <span id="infoLokasi" class="text-gray-700 font-semibold text-right"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400 font-medium">Target Pohon:</span>
                    <span id="infoTarget" class="text-gray-700 font-semibold"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400 font-medium">Progres Saat Ini:</span>
                    <span id="infoProgres" class="text-gray-700 font-semibold"></span>
                </div>
            </div>

            
            <div>
                <label for="jenis_pohon_id" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Jenis Pohon <span class="text-red-400">*</span></label>
                <select name="jenis_pohon_id" id="jenis_pohon_id" required
                        class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                    <option value="">-- Pilih Jenis Pohon --</option>
                    <?php $__currentLoopData = $jenisPohons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pohon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pohon->id); ?>" <?php echo e(old('jenis_pohon_id') == $pohon->id ? 'selected' : ''); ?>>
                            <?php echo e($pohon->nama); ?> (<?php echo e($pohon->nama_latin ?? '-'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['jenis_pohon_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="jumlah_tertanam" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Jumlah Pohon Tertanam <span class="text-red-400">*</span></label>
                <input type="number" name="jumlah_tertanam" id="jumlah_tertanam" required
                       value="<?php echo e(old('jumlah_tertanam')); ?>" placeholder="Masukkan jumlah pohon (>= 0)"
                       class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                <?php $__errorArgs = ['jumlah_tertanam'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="catatan" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Catatan Lapangan <span class="text-gray-300">(opsional)</span></label>
                <textarea name="catatan" id="catatan" rows="3" maxlength="500" placeholder="Tambahkan catatan hasil penanaman..."
                          class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all resize-none"><?php echo e(old('catatan')); ?></textarea>
                <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="flex gap-3 pt-3 border-t border-gray-50">
                <a href="<?php echo e(route('petugas.dashboard')); ?>"
                   class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 font-semibold text-sm rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center min-h-[44px]">
                    Batal
                </a>
                <button type="submit" id="btnSubmit"
                        class="flex-1 px-4 py-2.5 bg-[#1a8245] text-white font-semibold text-sm rounded-xl hover:bg-green-800 transition-colors flex items-center justify-center gap-2 min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Realisasi
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Data kegiatan diparsing ke format JS
    const kegiatansData = <?php echo json_encode($kegiatans, 15, 512) ?>;

    const selectKegiatan = document.getElementById('kegiatan_id');
    const infoBox = document.getElementById('kegiatanInfoBox');
    const infoLokasi = document.getElementById('infoLokasi');
    const infoTarget = document.getElementById('infoTarget');
    const infoProgres = document.getElementById('infoProgres');

    function updateKegiatanInfo() {
        const id = selectKegiatan.value;
        const kegiatan = kegiatansData.find(k => k.id == id);
        if (kegiatan) {
            infoLokasi.textContent = kegiatan.lokasi_lahan ? kegiatan.lokasi_lahan.alamat : '-';
            infoTarget.textContent = new Intl.NumberFormat('id-ID').format(kegiatan.target_pohon) + ' Pohon';
            infoProgres.textContent = new Intl.NumberFormat('id-ID').format(kegiatan.realisasi_pohon) + ' Pohon';
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }
    }

    selectKegiatan.addEventListener('change', updateKegiatanInfo);

    // Panggil sekali untuk inisialisasi jika ada old value atau pre-selected
    if (selectKegiatan.value) {
        updateKegiatanInfo();
    }

    // Submit listener untuk warning confirmation (AC-5)
    document.getElementById('realisasiForm').addEventListener('submit', function(e) {
        const id = selectKegiatan.value;
        const kegiatan = kegiatansData.find(k => k.id == id);
        if (kegiatan) {
            const jumlahInput = parseInt(document.getElementById('jumlah_tertanam').value) || 0;
            if (jumlahInput > kegiatan.target_pohon) {
                if (!confirm("Jumlah melebihi target kegiatan. Yakin ingin menyimpan?")) {
                    e.preventDefault();
                    return false;
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\egiag\Downloads\Gabungan\greennovate_final\resources\views/petugas/realisasi.blade.php ENDPATH**/ ?>