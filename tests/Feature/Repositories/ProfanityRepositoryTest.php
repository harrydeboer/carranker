<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Repositories\ProfanityRepository;
use Tests\TestCase;
use App\Models\Profanity;

class ProfanityRepositoryTest extends TestCase
{
    private ProfanityRepository $profanityRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->profanityRepository = $this->app->make(ProfanityRepository::class);
    }

    public function testGetProfanityNames()
    {
        $profanity = $this->profanityRepository->get(1);

        $profanities = explode(' ', $this->profanityRepository->getProfanityNames());
        $this->assertTrue(in_array($profanity->getName(), $profanities));
    }

    public function testValidate()
    {
        $badWord = Profanity::factory()->create();

        $this->assertFalse($this->profanityRepository->validate($badWord->getName()));

        $this->assertTrue($this->profanityRepository->validate("hey there"));

        $this->assertTrue($this->profanityRepository->validate(null));
    }
}
