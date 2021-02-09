<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Elasticsearch;

use App\Repositories\Interfaces\MakeRepositoryInterface;
use App\Repositories\Interfaces\ModelRepositoryInterface;
use Tests\FeatureTestCase;
use App\Models\Elasticsearch\Make;
use App\Models\Elasticsearch\Model;

class MakeRepositoryTest extends FeatureTestCase
{
    private MakeRepositoryInterface $makeRepository;
    private Make $make;
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeRepository = $this->app->make(MakeRepositoryInterface::class);
        $modelRepository = $this->app->make(ModelRepositoryInterface::class);
        $modelEloquent = \App\Models\MySQL\Model::factory()->create();
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
