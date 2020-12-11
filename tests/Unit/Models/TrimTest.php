<?php

namespace Tests\Unit\Models;

use App\Models\Aspect;
use App\Models\Trim;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimTest extends TestCase
{
    use DatabaseMigrations;

    public function testTrimInDB()
    {
        $trim = Trim::factory()->create();

        $assertArray = [
            'name' => $trim->getName(),
            'model_id' => $trim->getModel()->getId(),
            'make' => $trim->getMakename(),
            'model' => $trim->getModelname(),
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
        foreach (Aspect::getAspects() as $aspect) {
            $assertArray[$aspect] = $trim->getAspect($aspect);
        }
        $this->assertDatabaseHas('trims', $assertArray);

        $trimDB = Trim::find($trim->getId());
        $this->assertTrue($trimDB->testAttributesMatchFillable());
    }
}
