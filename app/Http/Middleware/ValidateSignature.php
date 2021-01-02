<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Routing\Exceptions\InvalidSignatureException;
use \Closure;

class ValidateSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $relative
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next, $relative = null)
    {
        $isSecure = $request->server->get('HTTPS');

        if (!$isSecure && (env('APP_ENV') === 'acceptance' || env('APP_ENV') === 'production')) {
            $request->server->set('HTTPS', 'on');
        }

        if ($request->hasValidSignature($relative !== 'relative')) {
            $request->server->set('HTTPS', $isSecure);
            return $next($request);
        }

        throw new InvalidSignatureException;
    }
}
