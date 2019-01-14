<?php

declare(strict_types=1);

namespace Tests\Feature\Buildup;

use Tests\TestCase;

class BuildupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->artisan('GetCMSData:getData')->execute();
        $this->artisan('ImportSQLFiles')->execute();
        $this->artisan('GetFXRate:get')->execute();
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }
}