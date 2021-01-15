<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\FXRate;
use App\Repositories\FXRateRepository;
use Tests\FeatureTestCase;

class FXRateRepositoryTest extends FeatureTestCase
{
    private FXRateRepository $fXRateRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fXRateRepository = $this->app->make(FXRateRepository::class);
    }

    public function testGetByName()
    {
        $fXRate = FXRate::factory()->create();
        $fXRateFromDb = $this->fXRateRepository->getByName($fXRate->getName());

        $this->assertEquals($fXRate->getId(), $fXRateFromDb->getId());
    }
}
