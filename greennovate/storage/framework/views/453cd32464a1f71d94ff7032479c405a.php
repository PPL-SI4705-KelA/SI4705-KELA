@extends('layouts.auth')

@section('title', __('Profile & Settings') . ' - Greennovate')

@section('content')
<div class="w-full max-w-4xl px-6 mt-6 mb-16">

    {{-- Back to Dashboard --}}
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-700 transition mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('Back to Dashboard') }}
    </a>

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Profile & Settings') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('Manage your personal information, security, and preferences') }}</p>
    </div>

    {{-- Profile Hero Card --}}
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#1b7b43] to-emerald-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg ring-4 ring-green-100 flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm mt-0.5">{{ $user->email }}</p>
                <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ $user->is_active ? __('Active') : __('Inactive') }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('role_' . $user->role) }}
                    </span>
                    @if($user->city)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $user->city }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="text-center sm:text-right text-xs text-gray-400 flex-shrink-0">
                <p>{{ __('Member Since') }}</p>
                <p class="font-semibold text-gray-600">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Success Toast --}}
    @if(session('success'))
        <div id="success-toast" class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200 shadow-sm animate-slide-in">
            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="font-medium text-sm">{{ session('success') }}</span>
            <button onclick="document.getElementById('success-toast').remove()" class="ml-auto text-green-400 hover:text-green-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Tabbed Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200 bg-gray-50/60">
            <nav class="flex" id="tab-nav">
                <button type="button" onclick="switchTab('profile')" id="tab-btn-profile"
                    class="tab-btn active relative px-6 py-4 text-sm font-semibold text-green-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Profile') }}
                </button>
                <button type="button" onclick="switchTab('security')" id="tab-btn-security"
                    class="tab-btn relative px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    {{ __('Security') }}
                </button>
                <button type="button" onclick="switchTab('preferences')" id="tab-btn-preferences"
                    class="tab-btn relative px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('Preferences') }}
                </button>
            </nav>
        </div>

        {{-- ============================================== --}}
        {{-- TAB 1: PROFILE --}}
        {{-- ============================================== --}}
        <div id="tab-profile" class="tab-content p-6 md:p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Personal Information') }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ __('Update your name, email, and phone number') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Full Name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                        {{ $errors->has('name') ? 'border-red-400 bg-red-50/50' : 'border-gray-300' }}">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Email Address') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                        placeholder="nama@email.com"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                        {{ $errors->has('email') ? 'border-red-400 bg-red-50/50' : 'border-gray-300' }}">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Phone Number') }}</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        placeholder="0812xxxxxxxx"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                        {{ $errors->has('phone') ? 'border-red-400 bg-red-50/50' : 'border-gray-300' }}">
                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('City') }}</label>
                    <input id="city" type="text" name="city" value="{{ old('city', $user->city) }}"
                        placeholder="{{ __('Enter your city') }}"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                        {{ $errors->has('city') ? 'border-red-400 bg-red-50/50' : 'border-gray-300' }}">
                    @error('city')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#1b7b43] to-[#15633a] text-white font-medium px-6 py-2.5 rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>

            {{-- Account Information --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Account Information') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Role') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ __('role_' . $user->role) }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Member Since') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">{{ __('Email Status') }}</p>
                        <p class="text-sm font-semibold flex items-center gap-1.5 {{ $user->email_verified_at ? 'text-green-700' : 'text-amber-600' }}">
                            @if($user->email_verified_at)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Verified') }}
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Not Verified') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================== --}}
        {{-- TAB 2: SECURITY --}}
        {{-- ============================================== --}}
        <div id="tab-security" class="tab-content p-6 md:p-8 hidden">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Change Password') }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ __('Ensure your account is using a strong password for security') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Current Password') }}</label>
                    <div class="relative">
                        <input id="current_password" type="password" name="current_password" required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                            {{ $errors->has('current_password') ? 'border-red-400 bg-red-50/50' : 'border-gray-300' }}">
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
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('New Password') }}</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm
                            {{ $errors->has('password') ? 'border-red-400 bg-red-50/50' : 'border-gray-300' }}">
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
                    <p class="text-xs text-gray-400 mt-1.5">{{ __('Min. 8 characters, uppercase/lowercase, numbers, symbols.') }}</p>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Confirm New Password') }}</label>
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
                        {{ __('Update Password') }}
                    </button>
                </div>
            </form>

            {{-- Danger Zone --}}
            <div class="mt-8 pt-6 border-t border-red-200">
                <h3 class="text-sm font-semibold text-red-700 mb-2 flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ __('Danger Zone') }}
                </h3>
                <div class="p-4 rounded-xl border border-red-200 bg-red-50/50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-red-800">{{ __('Delete Account') }}</p>
                            <p class="text-xs text-red-600 mt-0.5">{{ __('Once you delete your account, all data will be permanently removed. This action cannot be undone.') }}</p>
                        </div>
                        <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-red-600 text-white font-medium px-4 py-2 rounded-xl hover:bg-red-700 transition-all text-sm flex-shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================== --}}
        {{-- TAB 3: PREFERENCES --}}
        {{-- ============================================== --}}
        <div id="tab-preferences" class="tab-content p-6 md:p-8 hidden">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Language & Notifications') }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ __('Set your preferred language and notification channels') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.preferences') }}" class="space-y-8">
                @csrf
                @method('PATCH')

                {{-- Language Selection --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        {{ __('Language') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="locale" value="id" {{ old('locale', $user->locale) === 'id' ? 'checked' : '' }} class="peer sr-only">
                            <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-green-500 peer-checked:bg-green-50/50 hover:border-gray-300 peer-checked:shadow-sm">
                                <span class="text-2xl">🇮🇩</span>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">{{ __('Indonesian') }}</p>
                                    <p class="text-xs text-gray-500">Bahasa Indonesia</p>
                                </div>
                                <div class="ml-auto w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 flex items-center justify-center transition-colors">
                                    <div class="w-2.5 h-2.5 rounded-full bg-green-500 scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="locale" value="en" {{ old('locale', $user->locale) === 'en' ? 'checked' : '' }} class="peer sr-only">
                            <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-green-500 peer-checked:bg-green-50/50 hover:border-gray-300 peer-checked:shadow-sm">
                                <span class="text-2xl">🇺🇸</span>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">{{ __('English') }}</p>
                                    <p class="text-xs text-gray-500">English</p>
                                </div>
                                <div class="ml-auto w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 flex items-center justify-center transition-colors">
                                    <div class="w-2.5 h-2.5 rounded-full bg-green-500 scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('locale')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notification Toggles --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        {{ __('Notifications') }}
                    </h3>
                    <div class="space-y-3">
                        {{-- Email Notification --}}
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-gray-900">{{ __('Email Notifications') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('Receive activity updates and information via email') }}</p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="notif_email" value="0">
                                <input type="checkbox" name="notif_email" value="1" {{ old('notif_email', $user->notif_email) ? 'checked' : '' }}
                                    class="sr-only peer" id="toggle-email">
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-green-500 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>

                        {{-- Push Notification --}}
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-gray-900">{{ __('Push Notifications') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('Receive real-time notifications in your browser') }}</p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="notif_push" value="0">
                                <input type="checkbox" name="notif_push" value="1" {{ old('notif_push', $user->notif_push) ? 'checked' : '' }}
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
                        {{ __('Save Preferences') }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- Delete Account Modal --}}
<div id="delete-modal" class="{{ $errors->userDeletion->isNotEmpty() ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('delete-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-slide-in">
        <div class="text-center mb-4">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">{{ __('Delete Account') }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ __('Once you delete your account, all data will be permanently removed. This action cannot be undone.') }}</p>
        </div>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Enter your password to confirm') }}</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 border {{ $errors->userDeletion->has('password') ? 'border-red-500 bg-red-50/50' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition text-sm" placeholder="••••••••">
                @if ($errors->userDeletion->has('password'))
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition">
                    {{ __('I understand, delete my account') }}
                </button>
            </div>
        </form>
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
@endsection

@push('scripts')
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
        @if($errors->has('current_password') || $errors->has('password'))
            switchTab('security');
        @endif

        // If there are validation errors on preferences tab, auto-switch to preferences tab
        @if($errors->has('locale') || $errors->has('notif_email') || $errors->has('notif_push'))
            switchTab('preferences');
        @endif
    });
</script>
@endpush
