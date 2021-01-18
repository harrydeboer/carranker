<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Elastic;

use App\Repositories\Elastic\MakeRepository;
use App\Repositories\Elastic\ModelRepository;
use Tests\FeatureTestCase;
use App\Models\Elastic\Make;
use App\Models\Elastic\Model;

class MakeRepositoryTest extends FeatureTestCase
{
    private MakeRepository $makeRepository;
    private Make $make;
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeRepository = $this->app->make(MakeRepository::class);
        $modelRepository = $this->app->make(ModelRepository::class);
        $modelEloquent = \App\Models\Model::factory()->create();
        $this->artisan('process:queue');
        $this->model = $modelRepository->get($modelEloquent->getId());
        $this->make = $this->model->getMake();
    }

    public function testGetByName()
    {
        $makeFromDb = $this->makeRepository->getByName($this->make->getName());

        $this->assertEquals($this->make->getId(), $makeFromDb->getId());
    }

    public function testGetModelNames()
    {
        $this->assertNull($this->makeRepository->getModelNames(null));

        $modelNames = $this->makeRepository->getModelNames($this->make->getName());

        $this->assertEquals([$this->model->getName()], $modelNames);
    }

    public function testGetMakeNames()
    {
        $makeNames = $this->makeRepository->getMakeNames();

        $this->assertTrue(in_array($this->make->getName(), $makeNames));
    }

    public function testFindMakesForSearch()
    {
        $makes = $this->makeRepository->findForSearch($this->make->getName());
        $make = $makes[0];

        $this->assertEquals($make->getName(), $this->make->getName());
        $this->assertEquals($make->getId(), $this->make->getId());
    }
}
