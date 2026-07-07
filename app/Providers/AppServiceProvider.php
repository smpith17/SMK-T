<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\KartuTertelan;
use App\Observers\KartuObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator; // 1. Tambahkan import Paginator ini

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
        // 1. Daftarkan observer untuk log audit otomatis
        KartuTertelan::observe(KartuObserver::class);

        // 2. Paksa penggunaan HTTPS jika aplikasi berjalan di environment production
        // (Ini sangat bagus untuk keamanan di Railway)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 3. Gunakan styling Bootstrap 5 untuk komponen Pagination
        Paginator::useBootstrapFive();
    }
}