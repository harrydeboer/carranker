<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Admin;

use App\Models\User;
use App\Repositories\RoleRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    private RoleRepository $roleRepository;
    private Hasher $hasher;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleRepository = app()->make(RoleRepository::class);
        $this->hasher = app()->make(Hasher::class);
    }

    public function testHomePage()
    {
        $role = $this->roleRepository->getByName('admin');

        $password = 'secret';
        $user = User::factory()->create(['password' => $this->hasher->make($password)]);
        $user->getRoles()->attach($role->getId());

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->get('/admin');

        $response->assertStatus(200);
    }
}