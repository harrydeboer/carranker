<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Aspect;
use App\Models\Model;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelTest extends TestCase
{
    use DatabaseMigrations;

    public function testModelInDB()
    {
        $model = Model::factory()->create();
        $modelDB = Model::find($model->getId());

        $this->assertEquals($model->getWikiCarModel(), $modelDB->getWikiCarModel());

        $assertArray = [
            'name' => $model->getName(),
            'make_id' => $model->getMake()->getId(),
            'make_name' => $model->getMakeName(),
            'content' => $model->getContent(),
            'price' =>  $model->getPrice(1),
            'votes' => $model->getVotes(),
        ];

        foreach (Aspect::getAspects() as $aspect) {
            $assertArray[$aspect] = $model->getAspect($aspect);
        }
        $this->assertDatabaseHas('models', $assertArray);

        $this->assertTrue($modelDB->testAttributesMatchFillable());
    }
}
