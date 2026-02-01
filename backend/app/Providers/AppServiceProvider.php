<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\AppServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('ai', function ($request) {
            return Limit::perDay(config('sorsu.ai.daily_limit'))->by($request->user()?->id ?: $request->ip());
        });
    }
}
