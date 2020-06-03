<?php

declare(strict_types=1);

namespace Tests\Feature\Buildup;

use App\Models\Trim;
use App\Repositories\MakeRepository;
use App\Repositories\Elastic\TrimRepository;
use Tests\TestCase;

class BuildupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        factory('App\Models\Profanity')->create();
        factory('App\Models\FXRate')->create();
        factory(Trim::class)->create();
        factory(Trim::class)->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 6000]);
        factory(Trim::class)->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 7000]);
        factory(Trim::class)->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 11000]);
        factory(Trim::class)->create(['votes' => 31, 'framework' => 'Van']);
        factory(Trim::class)->create(['votes' => 25]);
        $this->artisan('getcmsdata')->execute();
        $this->artisan('getfxrate')->execute();
        $this->artisan('indexcars')->execute();
        $this->artisan('processqueue')->execute();
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }
}