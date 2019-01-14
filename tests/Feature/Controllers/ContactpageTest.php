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
}