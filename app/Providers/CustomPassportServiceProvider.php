<?php

declare(strict_types=1);

namespace App\Providers;

use Laravel\Passport\PassportServiceProvider as PassportServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;

class CustomPassportServiceProvider extends PassportServiceProvider
{
    protected function makePasswordGrant(): PasswordGrant
    {
        $grant = new PasswordGrant(
            new UserRepository(new WPHasher($this->app)),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}