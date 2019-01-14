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
        $this->assertEquals($jsonObject->require->{'johnpbloch/wordpress'}, $jsonObject->{'require-dev'}->{'wp-phpunit/wp-phpunit'});
    }
}