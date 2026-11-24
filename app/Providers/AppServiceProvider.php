<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/** Mendaftarkan dan menyiapkan layanan global aplikasi. */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Mendaftarkan binding layanan aplikasi ke dalam container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Menyiapkan layanan aplikasi setelah seluruh binding terdaftar.
     */
    public function boot(): void
    {
        //
    }
}
