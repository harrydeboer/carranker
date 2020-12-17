<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Elastic;

use App\Interfaces\Elastic\IMakeRepository;
use App\Interfaces\Elastic\IModelRepository;
use Tests\TestCase;

class MakeRepositoryTest extends TestCase
{
    private $makeRepository;
    private $make;
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->makeRepository = $this->app->make(IMakeRepository::class);
        $modelRepository = $this->app->make(IModelRepository::class);
        $this->model = $modelRepository->get(1);
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
        $makeNames = $this->makeRepository->getMakenames();

        $this->assertTrue(in_array($this->make->getName(), $makeNames));
    }

    public function testFindMakesForSearch()
    {
        $makeCollection = $this->makeRepository->findForSearch($this->make->getName());

        $make = $makeCollection->first();
        $this->assertEquals($make->getName(), $this->make->getName());
        $this->assertEquals($make->getId(), $this->make->getId());
    }
}
