<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\FeatureTestCase;

class ConfirmTest extends FeatureTestCase
{
    private User $user;

    protected function setUp(): void
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
        $hasher = app()->make(Hasher::class);

        $password = 'secretConfirm';
        $user = User::factory()->create(['password' => $hasher->make($password)]);

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => $password . 'NotValid',
        ]);

        $response->assertSessionHasErrors();

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => $password,
        ]);

        $response->assertRedirect(route('Home'));

        $response->assertSessionHasNoErrors();
    }
}