<?php

namespace Tests\Browser;

use App\Repositories\UserRepository;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LoginTest extends DuskTestCase
{
    public function testLogin()
    {
        $userRepository = new UserRepository();
        $user = $userRepository->get(4);

        $this->browse(function ($browser) use ($user)
        {
            $browser->visit('/login')
                ->type('user_email', $user->user_email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/');
        });
    }
}
