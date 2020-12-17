<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Interfaces\IFXRateRepository;
use Tests\TestCase;

class FXRateRepositoryTest extends TestCase
{
    private $fxrateRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->fxrateRepository = $this->app->make(IFXRateRepository::class);
    }

    public function testGetByName()
    {
        $fxrate = $this->fxrateRepository->get(1);
        $fxrateFromDb = $this->fxrateRepository->getByName($fxrate->getName());

        $this->assertEquals($fxrate->getId(), $fxrateFromDb->getId());
    }
}
