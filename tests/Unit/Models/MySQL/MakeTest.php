<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use App\Models\MySQL\Make;
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

        $makeDb = (new Make())->find($make->getId());
        $this->assertTrue($makeDb->testAttributesMatchFillable());
    }
}
