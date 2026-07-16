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
        View::composer('layouts.app', function ($view) {
            $count = (auth()->check() && auth()->user()->hasRole('partnership'))
                ? Vendor::where('status_approval', 'Pending')->count()
                : 0;
            $view->with('spVendorPending', $count);
        });
    }
}
