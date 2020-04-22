<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserInDB()
    {
        $user = factory(User::class)->create();

        $this->assertDatabaseHas('wp_users', [
            'user_login' => $user->getUsername(),
            'user_email' => $user->getEmail(),
            'user_pass' => $user->getAuthPassword(),
            'user_nicename' => $user->getUsername(),
            'user_url' => $user->getUserUrl(),
            'user_activation_key' => $user->getUserActivationKey(),
            'user_status' => $user->getUserStatus(),
            'display_name' => $user->getUsername(),
            'user_registered' => $user->getUserRegistered(),
            'remember_token' => $user->getRememberToken(),
        ]);
    }
}