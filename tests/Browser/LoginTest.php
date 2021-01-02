<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Providers\WPHasher;
use App\Repositories\UserRepository;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    public function testLogin()
    {
        $hasher = new WPHasher(app());

        $password = 'secret';
        $user = User::factory()->create([
            'user_pass' => $hasher->make($password)
        ]);

        $this->browse(function ($browser) use ($user)
        {
            $browser->visit('/login')
                ->type('user_email', $user->user_email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/');
        });

        $userRepository = new UserRepository();
        $userRepository->delete($user->getId());
    }
}
