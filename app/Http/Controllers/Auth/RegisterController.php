<?php

namespace App\Http\Controllers\Auth;

use App\Providers\WPHasher;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_login' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:wp_users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
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

    public function showRegistrationForm()
    {
        $data = [
            'title' => 'Register',
            'controller' => 'user',
            'page' => $this->pageRepository->getByName('register'),
        ];

        return view('auth.register')->with($data);
    }
}
