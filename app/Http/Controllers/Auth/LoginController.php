<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Response;

class LoginController extends BaseController
{
    protected $maxAttempts = 3;
    protected $decayMinutes = 5;
    protected $title = 'Login';

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function __construct(Guard $guard)
    {
        parent::__construct($guard);
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(): Response
    {
        $data = [
            'page' => $this->pageRepository->getByName('login'),
        ];

        return response()->view('auth.login', $data, 200);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'user_email';
    }
}