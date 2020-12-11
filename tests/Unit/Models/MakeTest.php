<?php

namespace Tests\Unit\Models;

use App\Models\Make;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MakeTest extends TestCase
{
    use DatabaseMigrations;

    public function testMakeInDB()
    {
        $make = Make::factory()->create();

        $this->assertDatabaseHas('makes', [
            'id' => $make->getId(),
            'name' => $make->getName(),
            'content' => $make->getContent(),
            'wiki_car_make' => $make->getWikiCarMake(),
        ]);

        $makeDB = Make::find($make->getId());
        $this->assertTrue($makeDB->testAttributesMatchFillable());
    }
}
