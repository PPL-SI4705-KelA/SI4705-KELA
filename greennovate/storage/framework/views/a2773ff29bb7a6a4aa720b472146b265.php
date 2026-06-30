<?php $__env->startSection('title', 'Greennovate - Pilih Lokasi & Jenis Pohon'); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-32 pb-16 min-h-screen bg-gray-50/50">
    <div class="max-w-4xl mx-auto px-6">
        
        <div class="bg-white rounded-3xl shadow-xl shadow-black/5 border border-gray-100 overflow-hidden">
            <div class="bg-[#0D8B41] px-8 py-8 text-white">
                <h1 class="text-2xl font-bold tracking-tight">Form Kontribusi Penanaman Pohon</h1>
                <p class="text-green-100 text-sm mt-1">Isi detail di bawah untuk mengotomatisasikan kontribusi penghijauan lahan bekas tambang.</p>
            </div>

            <div class="p-8">
                <form action="<?php echo e(route('pembelian.checkout')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Pilih Lokasi Penanaman Lahan</label>
                        <select name="lokasi_lahan_id" required 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#0D8B41] focus:bg-white transition-all">
                            <option value="" disabled selected>-- Pilih Lokasi Bekas Tambang --</option>
                            <?php $__currentLoopData = $lokasiLahans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lahan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lahan->id); ?>"><?php echo e($lahan->nama_lahan ?? $lahan->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Pilih Jenis Pohon</label>
                        <select name="jenis_pohon_id" id="jenis_pohon_id" required onchange="kalkulasiBiaya()"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#0D8B41] focus:bg-white transition-all">
                            <option value="" disabled selected>-- Pilih Jenis Pohon --</option>
                            <?php $__currentLoopData = $jenisPohons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pohon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option 
                                value="<?php echo e($pohon->id); ?>" 
                                data-harga="<?php echo e($pohon->harga); ?>"
                                >
                                <?php echo e($pohon->nama); ?> (<?php echo e($pohon->nama_latin); ?>)
                                 </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="3" placeholder="Contoh: Titip pesan untuk pelaksana lapangan..."
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#0D8B41] focus:bg-white transition-all resize-none"></textarea>
                    </div>

                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                        <h3 class="text-xs font-bold text-[#0D8B41] uppercase tracking-wider mb-3">Estimasi Komponen Biaya</h3>
                        <div class="space-y-2 text-xs font-medium text-gray-600">
                            <div class="flex justify-between">
                                <span>Harga Bibit Pohon:</span>
                                <span id="harga-bibit">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Operasional Layanan & Tanam:</span>
                                <span>Rp 25.000</span>
                            </div>
                            <hr class="border-gray-200/60 my-2">
                            <div class="flex justify-between items-center text-sm font-bold text-gray-900 pt-1">
                                <span>Total Pembayaran:</span>
                                <span id="total-biaya" class="text-xl text-red-600 font-extrabold">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold py-4 px-6 rounded-full transition-all text-center text-sm hover:shadow-lg hover:shadow-green-900/20">
                        Buat Pesanan & Invoice
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function kalkulasiBiaya() {

    const select = document.getElementById('jenis_pohon_id');

    if (!select.value) {
        return;
    }

    const hargaBibit = parseInt(
        select.options[select.selectedIndex].dataset.harga
    );

    const biayaLayanan = 25000;

    const total = hargaBibit + biayaLayanan;

    document.getElementById('harga-bibit').innerText =
        'Rp ' + hargaBibit.toLocaleString('id-ID');

    document.getElementById('total-biaya').innerText =
        'Rp ' + total.toLocaleString('id-ID');
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PPL-SI4705-KelA\greennovate\SI4705-KELA-main\greennovate\resources\views/pembelian/index.blade.php ENDPATH**/ ?>