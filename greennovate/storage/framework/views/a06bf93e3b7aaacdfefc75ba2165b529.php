<?php $__env->startSection('title', __('Profile & Settings') . ' - Greennovate'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl px-6 mt-6 mb-16">

    
    <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-700 transition mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <?php echo e(__('Back to Dashboard')); ?>

    </a>

    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900"><?php echo e(__('Profile & Settings')); ?></h1>
        <p class="text-gray-500 mt-1"><?php echo e(__('Manage your personal information, security, and preferences')); ?></p>
    </div>

    
    <?php if(session('success')): ?>
        <div id="success-toast" class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200 shadow-sm animate-slide-in">
            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="font-medium text-sm"><?php echo e(session('success')); ?></span>
            <button onclick="document.getElementById('success-toast').remove()" class="ml-auto text-green-400 hover:text-green-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        
        <div class="border-b border-gray-200 bg-gray-50/60">
            <nav class="flex" id="tab-nav">
                <button type="button" onclick="switchTab('profile')" id="tab-btn-profile"
                    class="tab-btn active relative px-6 py-4 text-sm font-semibold text-green-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <?php echo e(__('Profile')); ?>

                </button>
                <button type="button" onclick="switchTab('security')" id="tab-btn-security"
                    class="tab-btn relative px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <?php echo e(__('Security')); ?>

                </button>
                <button type="button" onclick="switchTab('preferences')" id="tab-btn-preferences"
                    class="tab-btn relative px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <?php echo e(__('Preferences')); ?>

                </button>
            </nav>
        </div>

        
        
        
        <div id="tab-profile" class="tab-content p-6 md:p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900"><?php echo e(__('Personal Information')); ?></h2>
                <p class="text-gray-500 text-sm mt-1"><?php echo e(__('Update your name, email, and phone number')); ?></p>
            </div>

            <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5"><?php echo e(__('Full Name')); ?></label>
                    <input id="name" type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                        <?php echo e($errors->has('name') ? 'border-red-400 bg-red-50/50' : 'border-gray-300'); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5"><?php echo e(__('Email Address')); ?></label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>"
                        placeholder="nama@email.com"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                        <?php echo e($errors->has('email') ? 'border-red-400 bg-red-50/50' : 'border-gray-300'); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5"><?php echo e(__('Phone Number')); ?></label>
                    <input id="phone" type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>"
                        placeholder="0812xxxxxxxx"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                        <?php echo e($errors->has('phone') ? 'border-red-400 bg-red-50/50' : 'border-gray-300'); ?>">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white font-medium px-6 py-2.5 rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <?php echo e(__('Save Changes')); ?>

                    </button>
                </div>
            </form>
        </div>

        
        
        
        <div id="tab-security" class="tab-content p-6 md:p-8 hidden">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900"><?php echo e(__('Change Password')); ?></h2>
                <p class="text-gray-500 text-sm mt-1"><?php echo e(__('Ensure your account is using a strong password for security')); ?></p>
            </div>

            <form method="POST" action="<?php echo e(route('profile.password')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5"><?php echo e(__('Current Password')); ?></label>
                    <div class="relative">
                        <input id="current_password" type="password" name="current_password" required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                            <?php echo e($errors->has('current_password') ? 'border-red-400 bg-red-50/50' : 'border-gray-300'); ?>">
                        <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5"><?php echo e(__('New Password')); ?></label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                            <?php echo e($errors->has('password') ? 'border-red-400 bg-red-50/50' : 'border-gray-300'); ?>">
                        <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5"><?php echo e(__('Min. 8 characters, uppercase/lowercase, numbers, symbols.')); ?></p>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5"><?php echo e(__('Confirm New Password')); ?></label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm border-gray-300">
                        <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white font-medium px-6 py-2.5 rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <?php echo e(__('Update Password')); ?>

                    </button>
                </div>
            </form>
        </div>

        
        
        
        <div id="tab-preferences" class="tab-content p-6 md:p-8 hidden">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900"><?php echo e(__('Language & Notifications')); ?></h2>
                <p class="text-gray-500 text-sm mt-1"><?php echo e(__('Set your preferred language and notification channels')); ?></p>
            </div>

            <form method="POST" action="<?php echo e(route('profile.preferences')); ?>" class="space-y-8">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        <?php echo e(__('Language')); ?>

                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="locale" value="id" <?php echo e(old('locale', $user->locale) === 'id' ? 'checked' : ''); ?> class="peer sr-only">
                            <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-green-500 peer-checked:bg-green-50/50 hover:border-gray-300 peer-checked:shadow-sm">
                                <span class="text-2xl">🇮🇩</span>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900"><?php echo e(__('Indonesian')); ?></p>
                                    <p class="text-xs text-gray-500">Bahasa Indonesia</p>
                                </div>
                                <div class="ml-auto w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 flex items-center justify-center transition-colors">
                                    <div class="w-2.5 h-2.5 rounded-full bg-green-500 scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="locale" value="en" <?php echo e(old('locale', $user->locale) === 'en' ? 'checked' : ''); ?> class="peer sr-only">
                            <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-green-500 peer-checked:bg-green-50/50 hover:border-gray-300 peer-checked:shadow-sm">
                                <span class="text-2xl">🇺🇸</span>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900"><?php echo e(__('English')); ?></p>
                                    <p class="text-xs text-gray-500">English</p>
                                </div>
                                <div class="ml-auto w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 flex items-center justify-center transition-colors">
                                    <div class="w-2.5 h-2.5 rounded-full bg-green-500 scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php $__errorArgs = ['locale'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <?php echo e(__('Notifications')); ?>

                    </h3>
                    <div class="space-y-3">
                        
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-gray-900"><?php echo e(__('Email Notifications')); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e(__('Receive activity updates and information via email')); ?></p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="notif_email" value="0">
                                <input type="checkbox" name="notif_email" value="1" <?php echo e(old('notif_email', $user->notif_email) ? 'checked' : ''); ?>

                                    class="sr-only peer" id="toggle-email">
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-green-500 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>

                        
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-gray-900"><?php echo e(__('Push Notifications')); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e(__('Receive real-time notifications in your browser')); ?></p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="notif_push" value="0">
                                <input type="checkbox" name="notif_push" value="1" <?php echo e(old('notif_push', $user->notif_push) ? 'checked' : ''); ?>

                                    class="sr-only peer" id="toggle-push">
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-green-500 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white font-medium px-6 py-2.5 rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <?php echo e(__('Save Preferences')); ?>

                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.4s ease-out;
    }

    /* Tab active indicator */
    .tab-btn.active {
        color: #15803d;
        font-weight: 600;
    }
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(to right, #1b7b43, #15633a);
        border-radius: 2px 2px 0 0;
    }
    .tab-btn:not(.active):hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Custom radio visual for language cards */
    input[type="radio"]:checked + div {
        border-color: #22c55e;
        background-color: rgba(240, 253, 244, 0.5);
    }
    input[type="radio"]:checked + div .w-5.h-5 {
        border-color: #22c55e;
    }
    input[type="radio"]:checked + div .w-2\.5 {
        transform: scale(1);
    }

    /* Toggle switch animation */
    .peer:checked ~ div:last-child {
        transform: translateX(1.25rem);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // =============================================
    // Tab Switching Logic
    // =============================================
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

        // Deactivate all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.remove('text-green-700', 'font-semibold');
            btn.classList.add('text-gray-500', 'font-medium');
        });

        // Show selected tab content
        const tabContent = document.getElementById('tab-' + tabName);
        if (tabContent) {
            tabContent.classList.remove('hidden');
        }

        // Activate selected tab button
        const tabBtn = document.getElementById('tab-btn-' + tabName);
        if (tabBtn) {
            tabBtn.classList.add('active');
            tabBtn.classList.add('text-green-700', 'font-semibold');
            tabBtn.classList.remove('text-gray-500', 'font-medium');
        }
    }

    // =============================================
    // Password Toggle Visibility
    // =============================================
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const eyeOpen = button.querySelector('.eye-open');
        const eyeClosed = button.querySelector('.eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // =============================================
    // Auto-dismiss success toast after 5 seconds
    // =============================================
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('success-toast');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'opacity 0.5s, transform 0.5s';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        // If there are validation errors on password tab, auto-switch to security tab
        <?php if($errors->has('current_password') || $errors->has('password')): ?>
            switchTab('security');
        <?php endif; ?>

        // If there are validation errors on preferences tab, auto-switch to preferences tab
        <?php if($errors->has('locale') || $errors->has('notif_email') || $errors->has('notif_push')): ?>
            switchTab('preferences');
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/awangwahyu/Downloads/SI4705-KELA-main/greennovate/resources/views/profile/edit.blade.php ENDPATH**/ ?>