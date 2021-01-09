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

    private ProfanityRepository $profanitiesRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->profanitiesRepository = $this->app->make(ProfanityRepository::class);
    }

    public function testContactForm()
    {
        $validator = new ContactValidator($this->profanitiesRepository->all());

        $request = request();
        $request->setMethod('POST');
        $request->request->add([
                                   'email' => 'test@test.com',
                                   'subject' => 'Test',
                                   'name' => 'Test',
                                   'message' => 'Test',
                                   'reCAPTCHAToken' => 'notUsedInTests',
                               ]);

        $this->assertIsArray($validator->validate($request));
    }
}
