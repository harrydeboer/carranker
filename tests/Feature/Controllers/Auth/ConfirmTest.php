<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Tests\TestCase;

class ConfirmTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testConfirmPage()
    {
        $response = $this->actingAs($this->user)->get(route('password.confirm'));

        $response->assertStatus(200);
    }

    public function testConfirm()
    {
        $response = $this->actingAs($this->user)->post(route('password.confirm'), [
            'password' => $this->user->getPassword(),
        ]);

        $response->assertStatus(302);
    }
}