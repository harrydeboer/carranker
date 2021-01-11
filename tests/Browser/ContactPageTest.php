<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class ContactPageTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function testContactPage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('contactPage'));

            $browser->type('email', 'test@test.com');
            $browser->type('name', 'Test');
            $browser->type('subject', 'test');
            $browser->type('message', 'test');

            $browser->press('Send');

            $browser->waitFor('#errorDisplay', 30);

            $this->assertEmpty($browser->driver->manage()->getLog('browser'));
        });
    }
}
