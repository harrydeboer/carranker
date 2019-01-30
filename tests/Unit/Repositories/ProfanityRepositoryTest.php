<?php

declare(strict_types=1);

use App\Models\Profanity;
use App\Repositories\ProfanityRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfanityRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $profanityRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->profanityRepository = new ProfanityRepository();
    }

    public function testGetProfanityNames()
    {
        $profanity = factory(Profanity::class)->create();

        $this->assertEquals($this->profanityRepository->getProfanityNames(), $profanity->getName());
    }

    public function testValidate()
    {
        $badWord = factory(\App\Models\Profanity::class)->create();

        $this->assertFalse($this->profanityRepository->validate($badWord->getName()));

        $this->assertTrue($this->profanityRepository->validate("hey there"));

        $this->assertTrue($this->profanityRepository->validate(null));
    }
}