<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Donasi::observe(\App\Observers\DonasiObserver::class);
        View::composer('*', function ($view) {
            $unreadChatCount = 0;
            if (Auth::check() && Auth::user()->role === 'user') {
                $unreadChatCount = Message::where('sender_id', '!=', Auth::id())
                    ->whereHas('conversation', function($q) {
                        $q->where('user_id', Auth::id());
                    })
                    ->whereRaw('is_read = false')
                    ->count();
            }
            $view->with('unreadChatCount', $unreadChatCount);
        });
    }
}