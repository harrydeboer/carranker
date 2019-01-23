<?php

declare(strict_types=1);

use App\Models\Make;
use App\Models\Model;
use App\Repositories\MakeRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MakeRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $makeRepository;
    private $make;
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->makeRepository = new MakeRepository();
        $this->model = factory(Model::class)->create();
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

        $this->assertEquals([$this->make->getName() => $this->make->getName()], $makeNames);
    }

    public function testFindMakesForSearch()
    {
        $makeCollection = $this->makeRepository->findMakesForSearch($this->make->getName());

        foreach ($makeCollection as $make) {
            $this->assertEquals($make->getName(), $this->make->getName());
            $this->assertEquals($make->getId(), $this->make->getId());
        }
    }
}