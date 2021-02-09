<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\FXRate;
use App\Repositories\Interfaces\FXRateRepositoryInterface;
use Tests\FeatureTestCase;

class FXRateRepositoryTest extends FeatureTestCase
{
    private FXRateRepositoryInterface $fXRateRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fXRateRepository = $this->app->make(FXRateRepositoryInterface::class);
    }

    public function testGetByName()
    {
        $fXRate = FXRate::factory()->create();
        $fXRateFromDb = $this->fXRateRepository->getByName($fXRate->getName());

        $this->assertEquals($fXRate->getId(), $fXRateFromDb->getId());
    }
}
