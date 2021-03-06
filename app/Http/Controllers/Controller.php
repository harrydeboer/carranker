<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MySQL\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @return User
     */
    protected function getCurrentUser(): ?Authenticatable
    {
        $guard = app()->make(Guard::class);

        return $guard->user();
    }
}
