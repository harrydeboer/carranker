<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Tests\FeatureTestCase;

class VerifyTest extends FeatureTestCase
{
    private User $user;

    protected function setUp(): void
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
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->getEmailForVerification()),
            ]
        );

        $this->assertNull($this->user->getEmailVerifiedAt());

        $response = $this->actingAs($this->user)->get($url);

        $this->assertNotNull($this->user->getEmailVerifiedAt());

        $response->assertRedirect(route('login'));
    }

    public function testResendPage()
    {
        $response = $this->actingAs($this->user)->post(route('verification.resend'));

        $response->assertRedirect(route('Home'));
    }
}
