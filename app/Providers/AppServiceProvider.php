<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Bắt buộc tất cả asset và link nội bộ đều chạy qua HTTPS khi deploy lên Internet
        if (config('app.env') === 'production' || str_contains(request()->header('host') ?? '', 'onrender.com')) {
            URL::forceScheme('https');
        }
    }
}