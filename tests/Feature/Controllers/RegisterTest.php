<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Repositories\UserRepository;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testRegister()
    {
        $repository = new UserRepository();

        $useremail = 'test@test.com';
        $response = $this->post('/register', [
            'user_login' => 'Test',
            'user_email' => $useremail,
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]);

        $response->assertRedirect('/');

        $user = $repository->getByEmail($useremail);
        $this->assertAuthenticatedAs($user);
    }
}
