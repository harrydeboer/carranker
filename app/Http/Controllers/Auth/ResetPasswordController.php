<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Providers\WPHasher;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function view(string $token)
    {
        return response()->view('auth.reset-password', [
            'title'=> 'Reset password', 'controller' => 'auth', 'token' => $token]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $hasher = new WPHasher(app());

        $status = Password::reset([
            'user_email' => $request->get('email'),
            'password' => $request->get('password'),
            'password_confirmation' => $request->get('password_confirmation'),
            'token' => $request->get('token')
        ],
            function ($user, $password) use ($request, $hasher) {
                $user->forceFill([
                    'user_pass' => $hasher->make($password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('auth')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
