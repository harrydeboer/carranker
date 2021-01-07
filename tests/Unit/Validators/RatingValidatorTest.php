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

    public function testRatingForm()
    {
        $form = new RatingValidator($this->app->make(ProfanityRepository::class));

        $request = request();
        $request->setMethod('POST');

        $requestParams = [
            'generation' => '2000-2004',
            'series' => 'Sedan',
            'trimId' => '1',
            'content' => null,
            'reCaptchaToken' => 'notusedintests',
        ];
        foreach (Aspect::getAspects() as $aspect) {
            $requestParams['star'][$aspect] = '8';
        }
        $request->request->add($requestParams);

        $this->assertTrue($form->validateFull($request, 'notvalid'));
    }
}