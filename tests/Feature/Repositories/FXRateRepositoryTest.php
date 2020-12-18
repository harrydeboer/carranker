<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Repositories\FXRateRepository;
use Tests\TestCase;

class FXRateRepositoryTest extends TestCase
{
    private FXRateRepository $fXRateRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->fXRateRepository = $this->app->make(FXRateRepository::class);
    }

    public function testGetByName()
    {
        $fxrate = $this->fXRateRepository->get(1);
        $fxrateFromDb = $this->fXRateRepository->getByName($fxrate->getName());

        $this->assertEquals($fxrate->getId(), $fxrateFromDb->getId());
    }
}
