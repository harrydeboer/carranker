<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Elasticsearch;

use App\Models\Elasticsearch\Model;
use App\Repositories\Interfaces\ModelRepositoryInterface;
use Tests\FeatureTestCase;

class ModelRepositoryTest extends FeatureTestCase
{
    private ModelRepositoryInterface $modelRepository;
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modelRepository = $this->app->make(ModelRepositoryInterface::class);
        $modelEloquent = \App\Models\MySQL\Model::factory()->create();
        $this->artisan('process:queue');
        $this->model = $this->modelRepository->get($modelEloquent->getId());
    }

    public function testGetByMakeModelName()
    {
        $modelFromDb = $this->modelRepository->getByMakeModelName($this->model->getMakeName(), $this->model->getName());

        $this->assertEquals($this->model->getId(), $modelFromDb->getId());
    }

    public function testGetModelNames()
    {
        $modelNames = $this->modelRepository->getModelNames();

        $this->assertTrue(in_array( $this->model->getMakeName() . ';' . $this->model->getName(), $modelNames));
    }

    public function testFindModelsForSearch()
    {
        $models = $this->modelRepository->findForSearch($this->model->getName());
        $model = $models[0];

        $this->assertEquals($model->getName(), $this->model->getName());
        $this->assertEquals($model->getId(), $this->model->getId());
    }
}
