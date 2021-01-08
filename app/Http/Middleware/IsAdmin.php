<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use \Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class IsAdmin
{
    public function __construct(
        private Guard $guard,
    ){}

    public function handle(Request $request, Closure $next)
    {
        $roles = $this->guard->user()?->getRoles()->get();

        if (!is_null($roles)) {
            foreach ($roles as $role) {
                if ($role->getName() === 'admin') {
                    return $next($request);
                }
            }
        }

        return redirect('login')->withErrors(['notAdmin' => 'You are not allowed to visit admin pages.']);
    }
}