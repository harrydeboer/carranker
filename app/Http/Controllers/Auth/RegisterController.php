<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Repositories\PageRepository;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Factory;
use Illuminate\Contracts\Hashing\Hasher;

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

    public function __construct(
        private Factory $validatorFactory,
        private UserRepository $userRepository,
        private PageRepository $pageRepository,
        private RoleRepository $roleRepository,
        private Hasher $hasher,
    )
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(): Response
    {
        $data = [
            'title' => 'Register',
            'controller' => 'auth',
            'page' => $this->pageRepository->getByName('register'),
        ];

        return response()->view('auth.register', $data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return $this->validatorFactory->make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => $this->hasher->make($data['password']),
                            ]);

        $role = $this->roleRepository->getByName('member');
        $user->getRoles()->attach($role->getId());

        return $user;
    }
}
