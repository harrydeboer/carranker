<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Repositories\PageRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    protected int $maxAttempts = 3;
    protected int $decayMinutes = 5;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function __construct(
        private PageRepository $pageRepository,
        private Guard $guard)
    {

    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = '/';

    public function showLoginForm(): Response
    {
        $user = $this->guard->user();
        return response()->view('auth.login', [
            'title' => 'Authentication',
            'controller' => 'auth',
            'isLoggedIn' => !is_null($user),
            'isEmailVerified' => $user?->hasVerifiedEmail(),
            'content' => $this->pageRepository->findByName('auth')?->getContent(),
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }
}
