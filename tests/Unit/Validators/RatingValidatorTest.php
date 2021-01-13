<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use App\Validators\RatingValidator;
use App\Models\Aspects;
use App\Repositories\ProfanityRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingValidatorTest extends TestCase
{
    use DatabaseMigrations;

    private ProfanityRepository $profanitiesRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profanitiesRepository = $this->app->make(ProfanityRepository::class);
    }

    public function testRatingForm()
    {
        $formData = [
            'trimId' => '1',
            'content' => null,
            'reCAPTCHAToken' => 'notUsedInTests',
        ];
        foreach (Aspects::getAspects() as $aspect) {
            $formData['star'][$aspect] = '8';
        }

        $validator = new RatingValidator($formData, $this->profanitiesRepository->all());

        $this->assertIsArray($validator->validate());
    }
}