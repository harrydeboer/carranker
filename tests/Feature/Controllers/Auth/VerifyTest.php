<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email_verified_at' => null]);
    }

    public function testNoticePage()
    {
        $response = $this->actingAs($this->user)->get(route('verification.notice'));

        $response->assertStatus(200);
    }

    public function testVerifyPage()
    {
        $response = $this->actingAs($this->user)->get(route('verification.verify', ['id' => '1', 'hash' => 'notValid']));

        $response->assertStatus(403);
    }

    public function testResendPage()
    {
        $response = $this->actingAs($this->user)->post(route('verification.resend'));

        $response->assertStatus(302);
    }
}