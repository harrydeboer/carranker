<?php

declare(strict_types=1);

namespace Tests\Unit\File;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ComposerTest extends TestCase
{
    use DatabaseMigrations;

    public function testVersionsWordpressTestSuiteMatch()
    {
        $string = file_get_contents(base_path() . '/composer.json');
        $jsonObject = json_decode($string);
        $requirements = $jsonObject->require;
        $this->assertEquals($requirements->{'johnpbloch/wordpress'}, $jsonObject->{'require-dev'}->{'wp-phpunit/wp-phpunit'});

        $extensions = get_loaded_extensions();
        foreach ($extensions as $extension) {
            if ($extension === 'Core' ||
                $extension === 'standard' ||
                $extension === 'xdebug' ||
                $extension === 'pdo_sqlite' ||
                $extension === 'sqlite3') {
                continue;
            } elseif ($extension === 'Zend OPcache') {
                $this->assertObjectHasAttribute('ext-Zend-OPcache', $requirements, "extension Zend-OPcache is missing in composer.json");
            } else {
                $this->assertObjectHasAttribute('ext-' . $extension, $requirements, "extension $extension is missing in composer.json");
            }
        }
    }
}