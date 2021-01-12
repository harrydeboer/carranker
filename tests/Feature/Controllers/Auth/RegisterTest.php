<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Repositories\UserRepository;
use Tests\TestCase;

class RegisterTest extends TestCase
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
        $useremail = 'test@test.com';
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => $useremail,
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]);

        $response->assertRedirect('/email/verify');

        $user = $this->userRepository->getByEmail($useremail);
        $this->assertAuthenticatedAs($user);
    }
}
