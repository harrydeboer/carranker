<?php

declare(strict_types=1);

namespace Tests\Unit\Forms;

use App\Forms\ContactForm;
use App\Repositories\ProfanityRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContactFormTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testContactForm()
    {
        $form = new ContactForm($this->app->make(ProfanityRepository::class));

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
