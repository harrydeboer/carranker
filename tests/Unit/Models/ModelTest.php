<?php

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
        $model = factory(Model::class)->create();
        $modelDB = Model::find($model->getId());

        $this->assertEquals($model->getWikiCarModel(), $modelDB->getWikiCarModel());

        $assertArray = [
            'name' => $model->getName(),
            'make_id' => $model->getMake()->getId(),
            'make' => $model->getMakename(),
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