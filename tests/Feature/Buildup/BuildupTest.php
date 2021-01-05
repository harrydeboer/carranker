<?php

declare(strict_types=1);

namespace Tests\Feature\Buildup;

use App\Models\Trim;
use App\Models\Profanity;
use App\Models\FXRate;
use App\Models\User;
use Tests\TestCase;

class BuildupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Profanity::factory()->create();
        FXRate::factory()->create();
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 6000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 7000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 11000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Van']);
        Trim::factory()->create(['votes' => 25]);
        $this->artisan('getcmsdata')->execute();
        $this->artisan('getfxrate')->execute();
        $this->artisan('processqueue')->execute();
        $this->artisan('indexcars')->execute();
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }
}
