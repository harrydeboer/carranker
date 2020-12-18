<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\PageRepository;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function view(): Response
    {
        return response()->view('auth.base', [
            'title' => 'Authentication',
            'isLoggedIn' => is_null(Auth::user()) ? false : true,
            'page' => $this->pageRepository->getByName('auth'),
        ], 200);
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
