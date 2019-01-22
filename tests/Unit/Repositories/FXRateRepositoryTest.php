<?php

declare(strict_types=1);

use App\Models\FXRate;
use App\Repositories\FXRateRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FXRateRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $fxrateRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->fxrateRepository = new FXRateRepository();
    }

    public function testGetByName()
    {
        $fxrate = factory(FXRate::class)->create();
        $fxrateFromDb = $this->fxrateRepository->getByName($fxrate->getName());

        $this->assertEquals($fxrate->getId(), $fxrateFromDb->getId());
    }
}