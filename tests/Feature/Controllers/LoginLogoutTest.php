<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Providers\WPHasher;
use App\Models\User;
use Tests\TestCase;

class LoginLogoutTest extends TestCase
{
    public function testLoginLogout()
    {
        $hasher = new WPHasher(app());

        $password = 'secret';
        $user = User::factory()->create([
            'user_pass' => $hasher->make($password),
        ]);

        $response = $this->post('/login', [
            'user_email' => $user->user_email,
            'password' => $password,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
