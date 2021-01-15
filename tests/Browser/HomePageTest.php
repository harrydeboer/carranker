<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class HomePageTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function testHomePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

	        $content = $browser->element('#carranker-logo')->getAttribute('outerHTML');
            $this->assertNotFalse(strpos($content, 'Car Ranker'));

            $browser->select('#nav-select-make', 'AC');

            $browser->click('#show-more');
            $browser->click('#show-less');
            $browser->click('#choose-preferences');
            $browser->click('#filter-top-form-show-all');
            $browser->click('#filter-top-form-reset');
            $browser->click('#filter-top-form-submit');

            $this->assertEmpty($browser->driver->manage()->getLog('browser'));
        });
    }
}
