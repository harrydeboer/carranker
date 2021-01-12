<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserInDB()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'remember_token' => $user->getRememberToken(),
            'email_verified_at' => $user->getEmailVerifiedAt(),
        ]);

        $userDb = (new User())->find($user->getId());

        $properties = array_merge($user->getFillable(), $user->getHidden(), [
                                      'email_verified_at',
                                      'created_at',
                                      'updated_at',
                                      ]);

        foreach ($userDb->getAttributes() as $key => $attribute) {
            if ($key !== 'id') {
                $this->assertTrue(in_array($key, $properties));
            }
        }
    }
}
