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

	        $content = $browser->element('#carrankerLogo')->getAttribute('outerHTML');
            $this->assertNotFalse(strpos($content, 'Car Ranker'));

            $browser->select('#navSelectMake', 'AC');

            $browser->click('#showMore');
            $browser->click('#showLess');
            $browser->click('#choosePreferences');
            $browser->click('#filterTopFormShowAll');
            $browser->click('#filterTopFormReset');
            $browser->click('#filterTopFormSubmit');

            $this->assertEmpty($browser->driver->manage()->getLog('browser'));
        });
    }
}
