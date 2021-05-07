<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use App\Validators\ContactValidator;
use App\Repositories\MySQL\ProfanityRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContactValidatorTest extends TestCase
{
    use DatabaseMigrations;

    private ProfanityRepository $profanitiesRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profanitiesRepository = $this->app->make(ProfanityRepository::class);
    }

    public function testContactForm()
    {
        $formData = [
            'email' => 'test@test.com',
            'subject' => 'Test',
            'name' => 'Test',
            'message' => 'Test',
            're-captcha-token' => 'notUsedInTests',
        ];

        $validator = new ContactValidator($formData);


        $this->assertIsArray($validator->validate());
    }
}
