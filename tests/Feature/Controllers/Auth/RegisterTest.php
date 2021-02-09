<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\MySQL\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Tests\FeatureTestCase;

class RegisterTest extends FeatureTestCase
{
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
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
