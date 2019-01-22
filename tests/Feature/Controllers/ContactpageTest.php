<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class ContactpageTest extends TestCase
{
    public function testContactpage()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function testSendMail()
    {
        $response = $this->post('/sendMail', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'subject' => 'Test',
            'Message' => 'Test',
            'reCaptchaToken' => 'notusedintests',
        ]);

        $response->assertStatus(200);
    }
}