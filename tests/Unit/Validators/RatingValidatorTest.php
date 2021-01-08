<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use App\Validators\RatingValidator;
use App\Models\Aspect;
use App\Repositories\ProfanityRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingValidatorTest extends TestCase
{
    use DatabaseMigrations;

    private ProfanityRepository $profanitiesRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->profanitiesRepository = $this->app->make(ProfanityRepository::class);
    }

    public function testRatingForm()
    {
        $validator = new RatingValidator($this->profanitiesRepository->all());

        $request = request();
        $request->setMethod('POST');

        $requestParams = [
            'generation' => '2000-2004',
            'series' => 'Sedan',
            'trimId' => '1',
            'content' => null,
            'reCaptchaToken' => 'notUsedInTests',
        ];
        foreach (Aspect::getAspects() as $aspect) {
            $requestParams['star'][$aspect] = '8';
        }
        $request->request->add($requestParams);

        $this->assertIsArray($validator->validate($request));
    }
}