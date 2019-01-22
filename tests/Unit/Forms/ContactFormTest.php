<?php

declare(strict_types=1);

namespace Tests\Unit\Forms;

use App\Forms\ContactForm;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    public function testContactForm()
    {
        $form = new ContactForm();

        $request = request();
        $request->setMethod('POST');
        $request->request->add([
            'email' => 'test@test.com',
            'subject' => 'Test',
            'name' => 'Test',
            'message' => 'Test',
            'reCaptchaToken' => 'notvalid',
        ]);

        $this->assertTrue($form->validateFull($request, 'notvalid'));
    }
}