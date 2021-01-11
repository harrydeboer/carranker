<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use \Closure;

class ValidateSignature
{
    /**
     * @throws InvalidSignatureException
     */
    public function handle(Request $request, Closure $next, $relative = null)
    {
        $isSecure = $request->server->get('HTTPS');

        if (!$isSecure && (env('APP_ENV') === 'acceptance' || env('APP_ENV') === 'production')) {
            $request->server->set('HTTPS', 'on');
        }

        if ($request->hasValidSignature($relative !== 'relative')) {
            $request->server->set('HTTPS', $isSecure);
            return $next($request);
        }

        throw new InvalidSignatureException();
    }
}
