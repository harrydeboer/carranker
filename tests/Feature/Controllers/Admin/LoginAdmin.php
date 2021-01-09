<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Admin;

use App\Models\User;
use App\Repositories\RoleRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\TestCase;

class LoginAdmin extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $roleRepository = app()->make(RoleRepository::class);
        $hasher = app()->make(Hasher::class);

        $role = $roleRepository->getByName('admin');

        $password = 'secret';
        $user = User::factory()->create(['password' => $hasher->make($password)]);
        $user->getRoles()->attach($role->getId());

        $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
    }
}
