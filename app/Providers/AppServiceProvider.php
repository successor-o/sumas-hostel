<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Surface persisted system settings (institution name, support
        // email, academic session) over the config('sumas.*') defaults so
        // every view that reads them reflects what admins saved.
        if (Schema::hasTable('settings')) {
            \App\Models\Setting::applyToConfig();
        }

        // Use our custom Bootstrap-styled pagination partial everywhere
        // instead of Laravel's default Tailwind pagination views.
        Paginator::defaultView('vendor.pagination.sumas');
        Paginator::defaultSimpleView('vendor.pagination.sumas');
    }
}
