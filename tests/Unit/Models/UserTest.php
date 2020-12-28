<?php

namespace Tests\Unit\Models;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserInDB()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas(env('WP_DB_PREFIX') . 'users', [
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
            'email_verified_at' => $user->getEmailVerifiedAt(),
        ]);

        $user = User::find($user->getId());

        $properties = array_merge($user->getFillable(), $user->getHidden());
        foreach ($user->getAttributes() as $key => $attribute) {
            if ($key !== 'ID') {
                $this->assertTrue(in_array($key, $properties));
            }
        }

        /** Test user table is in sync with laravel. The table can be altered by wordpress updates and these changes
          have to be synchronised with Laravel. */
        $result = DB::table(env('WP_DB_PREFIX') . 'users')->first();

        foreach ($result as $key => $column) {
            if ($key !== 'ID') {
                $this->assertTrue(in_array($key, $properties));
            }
        }
    }
}
