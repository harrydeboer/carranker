<?php

declare(strict_types=1);

namespace Tests\Unit\Forms;

use App\Forms\RatingForm;
use App\Models\Aspect;
use Tests\TestCase;

class RatingFormTest extends TestCase
{
    public function testRatingForm()
    {
        $form = new RatingForm();

        $request = request();
        $request->setMethod('POST');

        $requestParams = [
            'generation' => '2000-2004',
            'serie' => 'Sedan',
            'trimId' => '1',
            'content' => null,
            'reCaptchaToken' => 'notvalid',
        ];
        foreach (Aspect::getAspects() as $aspect) {
            $requestParams['star'][$aspect] = '8';
        }
        $request->request->add($requestParams);

        $this->assertTrue($form->validateFull($request, 'notvalid'));
    }
}