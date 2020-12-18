<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Repositories\UserRepository;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
    }

    public function testRegister()
    {
        $useremail = 'test@test.com';
        $response = $this->post('/register', [
            'user_login' => 'Test',
            'user_email' => $useremail,
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]);

        $response->assertRedirect('/');

        $user = $this->userRepository->getByEmail($useremail);
        $this->assertAuthenticatedAs($user);
    }
}
