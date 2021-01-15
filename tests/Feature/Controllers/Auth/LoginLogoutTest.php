<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\FeatureTestCase;

class LoginLogoutTest extends FeatureTestCase
{
    public function testLoginPage()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function testLoginLogout()
    {
        $hasher = app()->make(Hasher::class);

        $password = 'secret';
        $user = User::factory()->create([
            'password' => $hasher->make($password),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->getEmail(),
            'password' => $password,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
