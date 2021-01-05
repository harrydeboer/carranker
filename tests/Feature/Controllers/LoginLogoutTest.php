<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\TestCase;

class LoginLogoutTest extends TestCase
{
    public function testLoginLogout()
    {
        $hasher = app()->make(Hasher::class);

        $password = 'secret';
        $user = User::factory()->create([
            'password' => $hasher->make($password),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
