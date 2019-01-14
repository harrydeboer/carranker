<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Hashing\HashServiceProvider as BaseProvider;

class HashServiceProvider extends BaseProvider
{
    public function register()
    {
        $this->app->singleton('hash', function () {
            return new WPHasher($this->app);
        });
    }
}