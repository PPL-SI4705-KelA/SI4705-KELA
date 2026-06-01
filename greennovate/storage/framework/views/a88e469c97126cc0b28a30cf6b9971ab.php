@extends('layouts.auth')

@section('title', __('Dashboard') . ' - Greennovate')

@section('content')
<div class="w-full max-w-4xl px-6 mt-12">
    
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Dashboard') }}</h1>
        <p class="text-gray-500 mb-8">{{ __('Welcome back') }}, {{ Auth::user()->name }}!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 border border-green-100 p-6 rounded-lg text-center">
                <p class="text-sm text-green-800 font-medium mb-1">{{ __('Account Status') }}</p>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-200 text-green-800 text-sm font-semibold">
                    {{ Auth::user()->is_active ? __('Active') : __('Inactive') }}
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-100 p-6 rounded-lg text-center">
                <p class="text-sm text-blue-800 font-medium mb-1">{{ __('Your Role') }}</p>
                <h3 class="text-xl font-bold text-blue-900 uppercase">{{ Auth::user()->role }}</h3>
            </div>

            <div class="bg-orange-50 border border-orange-100 p-6 rounded-lg text-center">
                <p class="text-sm text-orange-800 font-medium mb-1">{{ __('Contact Info') }}</p>
                <p class="text-sm text-orange-900 truncate">{{ Auth::user()->email ?? Auth::user()->phone }}</p>
            </div>
        </div>

        {{-- Manage Profile Card --}}
        <div class="mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-5 rounded-xl border border-gray-200 bg-gradient-to-r from-green-50/50 to-emerald-50/50 hover:from-green-50 hover:to-emerald-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ __('Manage Profile') }}</p>
                        <p class="text-sm text-gray-500">{{ __('Manage your personal data and account settings') }}</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

    </div>
</div>
@endsection
