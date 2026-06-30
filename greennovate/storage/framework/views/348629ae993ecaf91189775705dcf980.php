<?php $__env->startSection('title', 'Greennovate – Pulihkan Lahan, Hijaukan Masa Depan'); ?>

<?php $__env->startSection('content'); ?>


<section class="relative pt-28 pb-10">
    <div class="max-w-6xl mx-auto px-6 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-6">

            
            <div class="hero-copy">
                
                <div class="inline-flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-3 py-1.5 mb-6 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-[#0D8B41]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs font-semibold text-gray-600 tracking-wide">SDG 15 · LIFE ON LAND</span>
                </div>

                
                <h1 class="text-5xl lg:text-[3.6rem] font-extrabold text-[#111827] leading-[1.1] tracking-tight mb-5">
                    Pulihkan Lahan,<br>
                    <span class="text-green-word">Hijau</span>kan Masa Depan
                </h1>

                <p class="text-sm text-gray-500 leading-relaxed mb-8 max-w-sm">
                    Bergabunglah dalam gerakan penghijauan area tambang rusak. Setiap pohon yang ditanam adalah langkah nyata menuju ekosistem daratan yang berkelanjutan.
                </p>

                
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('kegiatan.index')); ?>" id="hero-kegiatan-btn"
                       class="inline-flex items-center gap-2 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold px-6 py-3 rounded-lg transition-all hover:shadow-lg text-sm">
                        Lihat Kegiatan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('register')); ?>" id="hero-register-btn"
                       class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:border-[#0D8B41] font-semibold px-6 py-3 rounded-lg transition-all hover:shadow-sm text-sm">
                        Daftar Gratis
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="hero-image relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl">
                    <img
                        src="<?php echo e(asset('images/hero.png')); ?>"
                        alt="Aerial view of reforested land"
                        id="hero-img"
                        class="w-full h-[380px] object-cover"
                        onerror="
                            this.onerror=null;
                            this.style.display='none';
                            this.nextElementSibling.style.display='flex';
                        "
                    >
                    
                    <div style="display:none" class="w-full h-[380px] bg-gradient-to-br from-green-100 to-emerald-200 flex items-center justify-center">
                        <div class="text-center text-green-700">
                            <svg class="w-20 h-20 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm opacity-60">Foto Lahan Penghijauan</span>
                        </div>
                    </div>

                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

                    
                    <div class="absolute bottom-4 left-4 right-4 flex gap-2">
                        <div class="animate-float bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 flex-1 text-center shadow-md">
                            <div class="text-xs text-gray-400 font-medium mb-0.5">Pohon Ditanam</div>
                            <div class="text-base font-extrabold text-gray-900"><?php echo e(number_format($stats['pohon_ditanam'] ?: 0)); ?></div>
                        </div>
                        <div class="animate-float bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 flex-1 text-center shadow-md" style="animation-delay:0.3s">
                            <div class="text-xs text-gray-400 font-medium mb-0.5">Area Pulih</div>
                            <div class="text-base font-extrabold text-gray-900"><?php echo e($stats['total_lokasi']); ?> Ha</div>
                        </div>
                        <div class="animate-float bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 flex-1 text-center shadow-md" style="animation-delay:0.6s">
                            <div class="text-xs text-gray-400 font-medium mb-0.5">Relawan</div>
                            <div class="text-base font-extrabold text-gray-900"><?php echo e(number_format($stats['total_relawan'])); ?></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section id="stats" class="pb-14">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm reveal">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3c-1.2 5.4-5 7-5 11a5 5 0 0010 0c0-4-3.8-5.6-5-11z"/>
                    </svg>
                </div>
                <div class="text-2xl font-extrabold text-gray-900 mb-0.5"><?php echo e(number_format($stats['pohon_ditanam'] ?: 0)); ?></div>
                <div class="text-xs text-gray-400">Pohon Ditanam · batang</div>
            </div>

            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm reveal" style="transition-delay:.08s">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-extrabold text-gray-900 mb-0.5"><?php echo e(number_format($stats['total_lokasi'])); ?></div>
                <div class="text-xs text-gray-400">Lokasi Kegiatan · area</div>
            </div>

            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm reveal" style="transition-delay:.16s">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-extrabold text-gray-900 mb-0.5"><?php echo e(number_format($stats['total_relawan'])); ?></div>
                <div class="text-xs text-gray-400">Relawan Aktif · orang</div>
            </div>

            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm reveal" style="transition-delay:.24s">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div class="text-2xl font-extrabold text-gray-900 mb-0.5"><?php echo e(number_format($stats['total_program'] ?: 0)); ?></div>
                <div class="text-xs text-gray-400">Lahan Dipulihkan · hektar</div>
            </div>

        </div>
    </div>
</section>


<section id="lokasi" class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-end justify-between mb-8 reveal">
            <div>
                <span class="text-xs font-semibold text-[#0D8B41] uppercase tracking-widest block mb-1">Lokasi</span>
                <h2 class="text-2xl font-extrabold text-gray-900">Lahan Terdaftar</h2>
            </div>
            <?php if(auth()->guard()->check()): ?>
            <a href="#" class="text-sm font-semibold text-[#0D8B41] hover:underline flex items-center gap-1">
                Lihat semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <?php endif; ?>
        </div>

        <?php if($lokasi_terbaru->isEmpty()): ?>
            <div class="flex flex-col items-center justify-center py-16 text-center reveal">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Belum ada lokasi lahan terdaftar</p>
                <p class="text-xs text-gray-300 mt-1">Data akan muncul setelah admin menambahkan lokasi.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <?php $__currentLoopData = $lokasi_terbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="feature-card bg-[#f7f7f3] rounded-2xl overflow-hidden border border-gray-100 reveal" data-delay="<?php echo e($i * 100); ?>">
                    <div class="h-32 bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center">
                        <svg class="w-14 h-14 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 text-base mb-1"><?php echo e($lokasi->nama); ?></h3>
                        <?php if($lokasi->alamat): ?>
                        <div class="flex items-start gap-1.5 text-xs text-gray-400 mb-2">
                            <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?php echo e($lokasi->alamat); ?>

                        </div>
                        <?php endif; ?>
                        <?php if($lokasi->deskripsi): ?>
                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-2"><?php echo e($lokasi->deskripsi); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>


<section id="mission" class="py-14 bg-[#f0f0eb]">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-10 reveal">
            <span class="inline-block text-xs font-semibold text-[#0D8B41] uppercase tracking-widest mb-2">Misi Kami</span>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-3">Mengapa Greennovate?</h2>
            <p class="text-gray-500 text-sm max-w-lg mx-auto">
                Kami percaya perubahan nyata dimulai dari komunitas yang terhubung dan bergerak bersama.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <?php $__currentLoopData = [
                ['icon' => '🌍', 'title' => 'Dampak Nyata',       'desc' => 'Setiap aksi di Greennovate terukur dan terdokumentasi sehingga kamu tahu persis dampak yang kamu ciptakan.'],
                ['icon' => '🤝', 'title' => 'Komunitas Peduli',   'desc' => 'Bergabung dengan ribuan relawan dan donatur yang sama-sama bersemangat menjaga kelestarian alam.'],
                ['icon' => '📊', 'title' => 'Transparansi Penuh', 'desc' => 'Semua program, donasi, dan hasilnya tersaji secara terbuka. Tidak ada yang disembunyikan.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="feature-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm reveal" style="transition-delay:<?php echo e($i * 80); ?>ms">
                <div class="text-3xl mb-4"><?php echo e($item['icon']); ?></div>
                <h3 class="text-base font-bold text-gray-900 mb-2"><?php echo e($item['title']); ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?php echo e($item['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section id="how-it-works" class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-10 reveal">
            <span class="inline-block text-xs font-semibold text-[#0D8B41] uppercase tracking-widest mb-2">Cara Kerja</span>
            <h2 class="text-2xl font-extrabold text-gray-900">Mulai dalam 3 Langkah</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <?php $__currentLoopData = [
                ['num' => '01', 'title' => 'Daftar Akun',   'desc' => 'Buat akunmu gratis dalam hitungan detik. Tidak perlu kartu kredit.'],
                ['num' => '02', 'title' => 'Pilih Kegiatan', 'desc' => 'Jelajahi berbagai kegiatan penghijauan dan pilih yang sesuai minatmu.'],
                ['num' => '03', 'title' => 'Beri Dampak',    'desc' => 'Ikuti kegiatan dan pantau dampak nyata yang kamu ciptakan untuk bumi.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative text-center reveal" style="transition-delay:<?php echo e($i * 120); ?>ms">
                <?php if($i < 2): ?>
                    <div class="hidden md:block step-line"></div>
                <?php endif; ?>
                <div class="w-12 h-12 rounded-full bg-[#0D8B41] text-white font-extrabold text-sm flex items-center justify-center mx-auto mb-4 shadow-md">
                    <?php echo e($step['num']); ?>

                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2"><?php echo e($step['title']); ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto"><?php echo e($step['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<?php if(auth()->guard()->guest()): ?>
<section class="py-14 bg-[#0D8B41] relative overflow-hidden">
    <div class="orb w-[400px] h-[400px] bg-white/5 -top-[100px] right-[-50px]"></div>
    <div class="max-w-2xl mx-auto px-6 text-center relative z-10 reveal">
        <h2 class="text-2xl font-extrabold text-white mb-3">Siap Bergabung?</h2>
        <p class="text-green-100 text-sm mb-7 leading-relaxed">
            Bergabunglah dengan ribuan relawan yang sudah berkontribusi untuk bumi yang lebih hijau.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="<?php echo e(route('register')); ?>" id="cta-register-btn"
               class="inline-flex items-center gap-2 bg-white text-[#0D8B41] font-bold px-6 py-3 rounded-lg hover:bg-green-50 transition-all hover:shadow-xl text-sm">
                Daftar Gratis Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <a href="<?php echo e(route('login')); ?>" id="cta-login-btn"
               class="inline-flex items-center border-2 border-white/30 text-white font-semibold px-6 py-3 rounded-lg hover:bg-white/10 transition-all text-sm">
                Sudah punya akun? Masuk
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/awangwahyu/Documents/GitHub/SI4705-KELA/greennovate/resources/views/welcome.blade.php ENDPATH**/ ?>