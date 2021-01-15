<?php

declare(strict_types=1);

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function prepare(): void
    {
        static::startChromeDriver();
    }

    protected function driver(): RemoteWebDriver
    {
        (new ChromeOptions())->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

	    return RemoteWebDriver::create(
		    'http://selenium:4444/wd/hub', DesiredCapabilities::chrome()
	    );
    }
}
