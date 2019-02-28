<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CacheWithVarnish
{
    public function handle($request, Closure $next, int $cacheTimeInMinutes = null)
    {
        $response = $next($request);

        if (Auth::user()) {
            return $response;
        }

        return $response->withHeaders([
            config('varnish.cacheable_header_name') => '1',
            'Cache-Control' => 'public, max-age='. 60 * ($cacheTimeInMinutes ?? config('varnish.cache_time_in_minutes')),
        ]);
    }
}