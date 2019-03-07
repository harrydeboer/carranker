<?php

declare(strict_types=1);

namespace App\Providers;

use Laravel\Passport\PassportServiceProvider as PassportServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;

/** The hashing has to be the same as the wordpress hashing so that any user can log into wordpress as well as
 * Laravel. Laravel Passport has to have the wordpress hashing also.
 */
class CustomPassportServiceProvider extends PassportServiceProvider
{
    protected function makePasswordGrant(): PasswordGrant
    {
        $grant = new PasswordGrant(
            new UserRepository(new WPHasher(app())),
            app(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}