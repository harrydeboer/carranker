<?php

declare(strict_types=1);

namespace Tests\Unit\Models\MySQL;

use App\Models\Traits\AspectsTrait;
use App\Models\MySQL\Model;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelTest extends TestCase
{
    use DatabaseMigrations;

    public function testModelInDB()
    {
        $model = Model::factory()->create();

        $assertArray = [
            'id' => $model->getId(),
            'name' => $model->getName(),
            'make_id' => $model->getMake()->getId(),
            'make_name' => $model->getMakeName(),
            'content' => $model->getContent(),
            'price' =>  $model->getPrice(1),
            'votes' => $model->getVotes(),
            'wiki_car_model' => $model->getWikiCarModel(),
        ];

        foreach (AspectsTrait::getAspects() as $aspect) {
            $assertArray[$aspect] = $model->getAspect($aspect);
        }
        $this->assertDatabaseHas('models', $assertArray);

        $modelDb = (new Model())->find($model->getId());

        $this->assertEquals($model->getWikiCarModel(), $modelDb->getWikiCarModel());


        $this->assertTrue($modelDb->testAttributesMatchFillable());
    }
}
