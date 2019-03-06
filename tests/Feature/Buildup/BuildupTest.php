<?php

declare(strict_types=1);

namespace Tests\Feature\Buildup;

use Tests\TestCase;

class BuildupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        factory('App\Models\Profanity')->create();
        factory('App\Models\Trim')->create();
        $this->artisan('getcmsdata')->execute();
        $this->artisan('getfxrate')->execute();
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }
}