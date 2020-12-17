<?php

namespace App\Http\Controllers\Auth;

use App\Interfaces\IPageRepository;
use App\Interfaces\IUserRepository;
use App\Providers\WPHasher;
use App\Http\Controllers\Controller;
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

	protected $redirectTo = '/';
	private $userRepository;
	private $validatorFactory;
	private $pageRepository;

	public function __construct(Factory $validatorFactory,
                                IUserRepository $userRepository,
                                IPageRepository $pageRepository)
	{
		$this->userRepository = $userRepository;
		$this->pageRepository = $pageRepository;
		$this->validatorFactory = $validatorFactory;
		$this->middleware('guest');
	}

	protected function validator(array $data): Validator
	{
		return $this->validatorFactory->make($data, [
			'user_login' => 'required|string|max:255',
			'user_email' => 'required|string|email|max:255|unique:' . env('WP_DB_PREFIX') . 'users',
			'password' => 'required|string|min:6|confirmed',
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
			'page' => $this->pageRepository->getByName('register'),
		];

		return response()->view('auth.register', $data, 200);
	}
}
