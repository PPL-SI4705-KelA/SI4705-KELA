<?php

namespace App\Providers;

use App\Models\Kegiatan;
use App\Policies\KegiatanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Daftarkan Policy secara eksplisit (Laravel 11 tidak auto-discover di semua setup)
        Gate::policy(Kegiatan::class, KegiatanPolicy::class);
    }
}
