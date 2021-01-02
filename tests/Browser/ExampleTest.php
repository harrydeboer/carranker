<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
	        $content = $browser->element('#carrankerLogo')->getAttribute('outerHTML');
	        $this->assertTrue(strpos($content, 'Car Ranker') !== false);
        });
    }
}
