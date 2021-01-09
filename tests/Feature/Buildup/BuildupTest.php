<?php

declare(strict_types=1);

namespace Tests\Feature\Buildup;

use App\Models\Trim;
use App\Models\Profanity;
use App\Models\FXRate;
use App\Models\Role;
use Tests\TestCase;

class BuildupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Profanity::factory()->create();
        FXRate::factory()->create();
        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'editor']);
        Role::factory()->create(['name' => 'member']);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 6000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 7000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 11000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Van']);
        Trim::factory()->create(['votes' => 25]);
        $this->artisan('getfxrate')->execute();
        $this->artisan('indexcars')->execute();
    }

    public function testDummy()
    {
        $this->assertTrue(true);
    }
}
