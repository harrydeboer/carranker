<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use App\Validators\RatingValidator;
use App\Models\MySQL\AspectsTrait;
use App\Repositories\MySQL\ProfanityRepository;
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
            'trim-id' => '1',
            'content' => null,
            're-captcha-token' => 'notUsedInTests',
        ];
        foreach (AspectsTrait::getAspects() as $aspect) {
            $formData['star'][$aspect] = '8';
        }

        $validator = new RatingValidator($formData, $this->profanitiesRepository->all());

        $this->assertIsArray($validator->validate());
    }
}
