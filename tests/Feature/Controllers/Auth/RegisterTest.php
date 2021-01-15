<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\Role;
use App\Repositories\UserRepository;
use Tests\FeatureTestCase;

class RegisterTest extends FeatureTestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
    }

    public function testRegisterPage()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function testRegister()
    {
        Role::factory()->create(['name' => 'member']);

        $email = 'test@test.com';
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => $email,
            'password' => 'testTest',
            'password_confirmation' => 'testTest',
        ]);

        $response->assertRedirect('/email/verify');

        $user = $this->userRepository->getByEmail($email);
        $this->assertAuthenticatedAs($user);
    }
}
