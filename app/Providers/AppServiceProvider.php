<?php

namespace App\Providers;

use App\Models\Vendor;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Paksa HTTPS untuk seluruh URL dan aset Vite saat di production atau reverse proxy Render
        if ($this->app->environment('production') || str_starts_with(config('app.url'), 'https://') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('layouts.app', function ($view) {
            $count = (auth()->check() && auth()->user()->hasRole('partnership'))
                ? Vendor::where('status_approval', 'Pending')->count()
                : 0;
            $view->with('spVendorPending', $count);
        });
    }
}
