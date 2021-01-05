<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    public function testLogin()
    {
        $hasher = app()->make(Hasher::class);

        $password = 'secret';
        $user = User::factory()->create([
            'password' => $hasher->make($password)
        ]);

        $this->browse(function ($browser) use ($user)
        {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/');
        });

        $userRepository = new UserRepository();
        $userRepository->delete($user->getId());
    }
}
