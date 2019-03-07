<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Hashing\HashServiceProvider as BaseProvider;

/** The hashing has to be the same as the wordpress hashing so that any user can log into wordpress as well as
 * Laravel.
 */
class HashServiceProvider extends BaseProvider
{
    public function register()
    {
        $this->app->singleton('hash', function (): WPHasher {
            return new WPHasher(app());
        });
    }
}