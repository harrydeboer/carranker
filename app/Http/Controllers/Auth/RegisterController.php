<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\MySQL\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Validation\Factory;
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
        private UserRepositoryInterface $userRepository,
        private PageRepositoryInterface $pageRepository,
        private RoleRepositoryInterface $roleRepository,
        private Hasher $hasher,
    ) {
        $this->middleware('guest');
    }

    public function showRegistrationForm(): Response
    {
        $viewData = [
            'title' => 'Register',
            'controller' => 'auth',
            'content' => $this->pageRepository->findByName('register')?->getContent(),
        ];

        return response()->view('auth.register', $viewData);
    }

    protected function validator(array $data): Validator
    {
        return $this->validatorFactory->make($data, [
            'name' => ['required', 'string', 'max:255', 'alpha_num'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data): User
    {
        $user = new User([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => $this->hasher->make($data['password']),
                            ]);
        $user->save();

        $role = $this->roleRepository->getByName('member');
        $user->getRoles()->attach($role->getId());

        return $user;
    }
}
