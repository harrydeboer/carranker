<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\User;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testLoginLogout()
    {
        $user = factory(User::class)->create([
            'user_pass' => bcrypt($password = 'testtest'),
        ]);

        $response = $this->post('/login', [
            'user_email' => $user->user_email,
            'password' => $password,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $this->get('/logout');

        try {
            $this->assertAuthenticatedAs($user);
            $this->assertTrue(false, 'User is authenticated after logout.');
        } catch (ExpectationFailedException $exception) {
            $this->assertTrue(true);
        }
    }
}