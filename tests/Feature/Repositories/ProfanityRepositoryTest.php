<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Repositories\ProfanityRepository;
use Tests\TestCase;

class ProfanityRepositoryTest extends TestCase
{
    private $profanityRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->profanityRepository = new ProfanityRepository();
    }

    public function testGetProfanityNames()
    {
        $profanity = $this->profanityRepository->get(1);

        $profanities = explode(',', $this->profanityRepository->getProfanityNames());
        $this->assertTrue(in_array($profanity->getName(), $profanities));
    }

    public function testValidate()
    {
        $badWord = factory(\App\Models\Profanity::class)->create();

        $this->assertFalse($this->profanityRepository->validate($badWord->getName()));

        $this->assertTrue($this->profanityRepository->validate("hey there"));

        $this->assertTrue($this->profanityRepository->validate(null));
    }
}