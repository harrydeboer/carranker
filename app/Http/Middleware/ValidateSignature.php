<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use \Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use function GuzzleHttp\Psr7\str;

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
        if ($this->hasValidSignature($request, $relative !== 'relative')) {
            return $next($request);
        }

        throw new InvalidSignatureException;
    }

    /**
     * Determine if the given request has a valid signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $absolute
     * @return bool
     */
    private function hasValidSignature(Request $request, $absolute = true)
    {
        return $this->hasCorrectSignature($request, $absolute)
            && $this->signatureHasNotExpired($request);
    }

    private function hasCorrectSignature(Request $request, $absolute = true)
    {
        $url = $absolute ? $request->url() : '/'.$request->path();

        if (env('APP_ENV') === 'acceptance' || env('APP_ENV') === 'production') {
            $url = str_replace('https', 'http', $url);
            $url = str_replace('http', 'https', $url);
        }

        $original = rtrim($url.'?'.Arr::query(
                Arr::except($request->query(), 'signature')
            ), '?');

        $signature = hash_hmac('sha256', $original, env('APP_KEY'));

        return hash_equals($signature, (string) $request->query('signature', ''));
    }

    /**
     * Determine if the expires timestamp from the given request is not from the past.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function signatureHasNotExpired(Request $request)
    {
        $expires = $request->query('expires');

        return ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }
}
