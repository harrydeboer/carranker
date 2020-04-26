<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);
        if (env('APP_ENV') === 'acceptance' || env('APP_ENV') === 'production' ) {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
