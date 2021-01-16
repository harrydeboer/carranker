<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Tests\FeatureTestCase;

class ResetPasswordTest extends FeatureTestCase
{
    private User $user;

    protected function setUp(): void
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
        $response = $this->post(route('password.email'), [
            'email' => $this->user->getEmail(),
        ]);

        $response->assertRedirect(route('Home'));

        $tokens = DB::table('password_resets')->where('email', $this->user->getEmail())->get();

        $this->assertCount(1, $tokens);
    }

    public function testReset()
    {
        $token = Password::broker()->createToken($this->user);
        $response = $this->actingAs($this->user)->get(route('password.reset', ['token' => $token]));

        $response->assertStatus(200);

        $newPassword = 'newSecret';
        $response = $this->actingAs($this->user)->post(route('password.update'), [
            'token' => $token,
            'email' => $this->user->getEmail(),
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
            ]);

        $response->assertRedirect(route('Home'));

        $response = $this->post(route('login'), [
            'email' => $this->user->getEmail(),
            'password' => $newPassword,
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(route('Home'));
    }
}
