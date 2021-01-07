<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use App\Validators\ContactValidator;
use App\Repositories\ProfanityRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContactValidatorTest extends TestCase
{
    use DatabaseMigrations;

    public function testContactForm()
    {
        $form = new ContactValidator($this->app->make(ProfanityRepository::class));

        $request = request();
        $request->setMethod('POST');
        $request->request->add([
                                   'email' => 'test@test.com',
                                   'subject' => 'Test',
                                   'name' => 'Test',
                                   'message' => 'Test',
                                   'reCaptchaToken' => 'notusedintests',
                               ]);

        $this->assertTrue($form->validateFull($request, 'notvalid'));
    }
}
