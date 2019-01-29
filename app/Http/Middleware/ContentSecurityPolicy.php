<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class ContentSecurityPolicy
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', "default-src 'self' 'unsafe-inline'; img-src 'self' data:;" .
         " script-src 'self' 'unsafe-inline' https://www.googletagmanager.com/gtag/js https://www.google.com/recaptcha/api.js" .
        " https://www.gstatic.com; frame-src 'self' https://www.google.com/");

        return $response;
    }
}