<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Profanity;
use App\Repositories\ProfanityRepository;
use Tests\FeatureTestCase;

class ProfanityRepositoryTest extends FeatureTestCase
{
    private ProfanityRepository $profanityRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profanityRepository = $this->app->make(ProfanityRepository::class);
    }

    public function testGetProfanityNames()
    {
        $profanity = Profanity::factory()->create();

        $profanities = explode(' ', $this->profanityRepository->getProfanityNames());
        $this->assertTrue(in_array($profanity->getName(), $profanities));
    }
}
