<?php

namespace App\Http\Controllers\Auth;

use App\Providers\WPHasher;
use App\Repositories\PageRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Factory;

class RegisterController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	protected string $redirectTo = '/email/verify';

	public function __construct(private Factory $validatorFactory,
                                private UserRepository $userRepository,
                                private PageRepository $pageRepository)
	{
		$this->middleware('guest');
	}

	protected function validator(array $data): Validator
    {
        return $this->validatorFactory->make($data, [
            'user_login' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', 'unique:' . env('WP_DB_PREFIX') . 'users'],
            'user_pass' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

	protected function create(array $data): User
	{
		$hasher = new WPHasher(app());

		return $this->userRepository->create([
                'user_login' => $data['user_login'],
                'user_nicename' => $data['user_login'],
                'display_name' => $data['user_login'],
                'user_email' => $data['user_email'],
                'user_pass' => $hasher->make($data['password']),
                'user_registered' => date('Y-m-d H:i:s', time()),
            ]);
	}

	public function showRegistrationForm(): Response
	{
		$data = [
			'title' => 'Register',
            'controller' => 'auth',
			'page' => $this->pageRepository->getByName('register'),
		];

		return response()->view('auth.register', $data, 200);
	}
}
