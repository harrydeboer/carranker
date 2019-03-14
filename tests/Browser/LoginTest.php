<?php

namespace Tests\Browser;

use App\Providers\WPHasher;
use App\Repositories\UserRepository;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LoginTest extends DuskTestCase
{
    public function testLogin()
    {
        $hasher = new WPHasher(app());

        $password = 'secret';
        $user = factory('App\User')->create([
            'user_pass' => $hasher->make($password)
        ]);

        $this->browse(function ($browser) use ($user)
        {
            $browser->visit('/auth')
                ->type('user_email', $user->user_email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/');
        });

        $userRepository = new UserRepository();
        $userRepository->delete($user->getId());
    }
}
