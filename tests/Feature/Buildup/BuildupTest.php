<?php

declare(strict_types=1);

namespace Tests\Feature\Buildup;

use Tests\TestCase;

class BuildupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->artisan('getcmsdata')->execute();
        $this->artisan('importsqlfiles')->execute();
        $this->artisan('getfxrate')->execute();
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }
}