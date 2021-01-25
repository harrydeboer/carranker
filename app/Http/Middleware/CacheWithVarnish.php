<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheWithVarnish
{
    /**
     * Varnish needs cache even when there is a Laravel cookie.
     * An extra header is added to the response to tell varnish that the page must be cached.
     */
    public function handle(Request $request, Closure $next, int $cacheTimeInMinutes = null)
    {
        $response = $next($request);

        return $response->withHeaders([
                                          config('varnish.cacheable_header_name') => '1',
                                          'Cache-Control' => 'public, max-age=' . 60 *
                                              ($cacheTimeInMinutes ?? config('varnish.cache_time_in_minutes')),
                                      ]);
    }
}
