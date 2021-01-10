<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testRequestPage()
    {
        $response = $this->actingAs($this->user)->get(route('password.request'));

        $response->assertStatus(200);
    }

    public function testEmail()
    {
        $response = $this->actingAs($this->user)->post(route('password.email'), [
            'email' => $this->user->getEmail(),
        ]);

        $response->assertStatus(302);
    }

    public function testResetPage()
    {
        $response = $this->actingAs($this->user)->get(route('password.reset', ['token' => 'notValid']));

        $response->assertStatus(200);
    }

    public function testReset()
    {
        $response = $this->actingAs($this->user)->post(route('password.update'), [
            'token' => 'notValid',
            'email' => $this->user->getEmail(),
            'password' => $this->user->getPassword(),
            'password_confirmation' => $this->user->getPassword(),
            ]);

        $response->assertStatus(302);
    }
}