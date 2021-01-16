<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\FeatureTestCase;

class LoginAdmin extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Role::factory()->create(['name' => 'admin']);
        $roleRepository = app()->make(RoleRepository::class);
        $role = $roleRepository->getByName('admin');

        $hasher = app()->make(Hasher::class);
        $password = 'secret';
        $user = User::factory()->create(['password' => $hasher->make($password)]);
        $user->getRoles()->attach($role->getId());

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);
    }
}
