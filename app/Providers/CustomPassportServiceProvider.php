<?php

declare(strict_types=1);

namespace App\Providers;

use Laravel\Passport\PassportServiceProvider as PassportServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\RefreshTokenRepository;

class CustomPassportServiceProvider extends PassportServiceProvider
{
    /**
     * Create and configure a Password grant instance.
     *
     * @return \League\OAuth2\Server\Grant\PasswordGrant
     */
    protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
            new PassportUserRepository(new WPHasher($this->app)),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}