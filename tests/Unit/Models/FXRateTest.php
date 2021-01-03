<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\FXRate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FXRateTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetByName()
    {
        $fxrate = FXRate::factory()->create();

        $this->assertDatabaseHas('fx_rates', [
            'name' => $fxrate->getName(),
            'value' => $fxrate->getValue(),
        ]);

        $fxrateDB = FXRate::find($fxrate->getId());
        $this->assertTrue($fxrateDB->testAttributesMatchFillable());
    }
}
