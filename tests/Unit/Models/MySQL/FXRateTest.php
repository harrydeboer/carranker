<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use App\Models\MySQL\FXRate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FXRateTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetByName()
    {
        $fXRate = FXRate::factory()->create();

        $this->assertDatabaseHas('fx_rates', [
            'id' => $fXRate->getId(),
            'name' => $fXRate->getName(),
            'value' => $fXRate->getValue(),
        ]);

        $fXRateDb = (new FXRate())->find($fXRate->getId());
        $this->assertTrue($fXRateDb->testAttributesMatchFillable());
    }
}
