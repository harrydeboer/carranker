<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\FeatureTestCase;

class ContactPageTest extends FeatureTestCase
{
    public function testContactPage()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function testSendMail()
    {
        $response = $this->post('/contact', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'subject' => 'Test',
            'message' => 'Test',
            'reCAPTCHAToken' => 'notAValidToken',
        ]);

        $response->assertStatus(302);
    }
}
