<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class ContentSecurityPolicy
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (env('APP_ENV') === 'production' || env('APP_ENV') === 'acceptance') {
            $response->headers->set('Content-Security-Policy', "default-src 'self'; style-src 'self' " .
                "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css " .
                "https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css;" .
                " font-src 'self' https://fonts.gstatic.com/s/opensans/v13/cJZKeOuBrn4kERxqtaUH3ZBw1xU1rKptJj_0jans920.woff2" .
                " https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/fonts/ " .
                "https://fonts.gstatic.com/s/opensans/v15/; img-src 'self' data:;" .
                " script-src 'self' https://www.googletagmanager.com/gtag/js https://www.google.com/recaptcha/api.js" .
                " https://www.gstatic.com https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js " .
                "https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js " .
                "https://code.jquery.com/jquery-3.3.1.min.js; frame-src 'self' https://www.google.com/");
        }

        return $response;
    }
}