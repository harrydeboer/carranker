<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use App\Models\Traits\AspectsTrait;
use App\Models\MySQL\Trim;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimTest extends TestCase
{
    use DatabaseMigrations;

    public function testTrimInDB()
    {
        $trim = Trim::factory()->create();

        $assertArray = [
            'id' => $trim->getId(),
            'name' => $trim->getName(),
            'model_id' => $trim->getModel()->getId(),
            'make_name' => $trim->getMakeName(),
            'model_name' => $trim->getModelName(),
            'price' => $trim->getPrice(1),
            'votes' => $trim->getVotes(),
            'framework' => $trim->getFramework(),
            'year_begin' => $trim->getYearBegin(),
            'year_end' => $trim->getYearEnd(),
            'fuel' => $trim->getFuel(),
            'number_of_seats' => $trim->getNumberOfSeats(),
            'number_of_doors' => $trim->getNumberOfDoors(),
            'number_of_gears' => $trim->getNumberOfGears(),
            'gearbox_type' => $trim->getTransmission(),
            'max_trunk_capacity' => $trim->getMaxTrunkCapacity(),
            'engine_capacity' => $trim->getEngineCapacity(),
            'fueltank_capacity' => $trim->getFueltankCapacity(),
            'max_speed' => $trim->getMaxSpeed(),
            'full_weight' => $trim->getFullWeight(),
            'engine_power' => $trim->getEnginePower(),
            'acceleration' => $trim->getAcceleration(),
            'fuel_consumption' => $trim->getFuelConsumption(),
        ];
        foreach (AspectsTrait::getAspects() as $aspect) {
            $assertArray[$aspect] = $trim->getAspect($aspect);
        }
        $this->assertDatabaseHas('trims', $assertArray);

        $trimDb = (new Trim())->find($trim->getId());
        $this->assertTrue($trimDb->testAttributesMatchFillable());
    }
}
