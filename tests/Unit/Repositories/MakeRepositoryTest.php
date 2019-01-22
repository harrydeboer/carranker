<?php

declare(strict_types=1);

use App\Models\Make;
use App\Repositories\MakeRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MakeRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $makeRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->makeRepository = new MakeRepository();
    }

    public function testGetByName()
    {
        $make = factory(Make::class)->create();
        $makeFromDb = $this->makeRepository->getByName($make->getName());

        $this->assertEquals($make->getId(), $makeFromDb->getId());
    }
}