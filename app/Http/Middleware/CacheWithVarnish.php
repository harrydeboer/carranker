<?php

declare( strict_types=1 );

namespace App\Http\Middleware;

use Closure;

class CacheWithVarnish
{
	public function handle($request, Closure $next, int $cacheTimeInMinutes = null)
	{
		$response = $next($request);

		if (env('APP_ENV') === 'production') {
			return $response->withHeaders([
				config('varnish.cacheable_header_name') => '1',
				'Cache-Control' => 'public, max-age='. 60 * ($cacheTimeInMinutes ?? config('varnish.cache_time_in_minutes')),
			]);
		}

		return $response;
	}
}