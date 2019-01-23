<?php

declare(strict_types=1);

use App\Repositories\ModelRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $modelRepository;
    private $model;
    private $review;

    public function setUp()
    {
        parent::setUp();
        $this->modelRepository = new ModelRepository();
        $this->review = factory(\App\Models\Rating::class)->create(['content' => 'notnull']);
        $this->model = $this->review->getModel();
    }

    public function testGetByMakeModelName()
    {
        $modelFromDb = $this->modelRepository->getByMakeModelName($this->model->getMakename(), $this->model->getName());

        $this->assertEquals($this->model->getId(), $modelFromDb->getId());
    }

    public function testGetModelNames()
    {
        $modelNames = $this->modelRepository->getModelNames();

        foreach ($modelNames as $modelName) {
            $this->assertEquals($modelName, $this->model->getMakename() . ';' . $this->model->getName());
        }
    }

    public function testFindModelsForSearch()
    {
        $modelCollection = $this->modelRepository->findModelsForSearch($this->model->getName());

        foreach ($modelCollection as $model) {
            $this->assertEquals($model->getName(), $this->model->getName());
            $this->assertEquals($model->getId(), $this->model->getId());
        }
    }

    public function testGetReviews()
    {
        $reviews = $this->modelRepository->getReviews($this->model, 1);

        foreach ($reviews as $review) {
            $this->assertEquals($this->review->getId(), $review->getId());
        }
    }

    public function testGetNumOfReviews()
    {
        $number = $this->modelRepository->getNumOfReviews($this->model);

        $this->assertEquals($number, 1);
    }
}