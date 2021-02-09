<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\MySQL;

use App\Models\MySQL\Profanity;
use App\Repositories\Interfaces\ProfanityRepositoryInterface;
use Tests\FeatureTestCase;

class ProfanityRepositoryTest extends FeatureTestCase
{
    private ProfanityRepositoryInterface $profanityRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profanityRepository = $this->app->make(ProfanityRepositoryInterface::class);
    }

    public function testGetProfanityNames()
    {
        $profanity = Profanity::factory()->create();

        $profanities = explode(' ', $this->profanityRepository->getProfanityNames());
        $this->assertTrue(in_array($profanity->getName(), $profanities));
    }
}
