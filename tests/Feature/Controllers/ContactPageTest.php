<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function testContactpage()
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
            'reCaptchaToken' => 'notavalidtoken',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Could not deliver mail. Try again later.');
    }
}
