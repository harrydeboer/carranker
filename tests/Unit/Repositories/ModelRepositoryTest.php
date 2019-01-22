<?php

declare(strict_types=1);

use App\Models\Model;
use App\Repositories\ModelRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $modelRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->modelRepository = new ModelRepository();
    }

    public function testGetByMakeModelName()
    {
        $model = factory(Model::class)->create();
        $modelFromDb = $this->modelRepository->getByMakeModelName($model->getMakename(), $model->getName());

        $this->assertEquals($model->getId(), $modelFromDb->getId());
    }
}