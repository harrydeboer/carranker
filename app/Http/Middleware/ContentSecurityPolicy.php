<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        /** When the app env is local the Laravel error message must be displayed with inline styling and scripting.
         * Therefore the Content Security Policy is not demanded locally. The other app envs need this security.
         * Testing has to be done with this security enabled also.
         */
        if (env('APP_ENV') !== 'local') {
            $response->headers->set('Content-Security-Policy', "default-src 'self'; style-src 'self'" .
                " https://fonts.googleapis.com/css2?family=Open+Sans&display=swap;" .
                " font-src 'self' https://fonts.gstatic.com/s/opensans/v18 " .
                "img-src 'self' data:;" .
                " script-src 'self' https://www.googletagmanager.com/gtag/js https://www.google.com/recaptcha/api.js" .
                " https://www.gstatic.com; frame-src 'self' https://www.google.com/");
        }

        return $response;
    }
}
