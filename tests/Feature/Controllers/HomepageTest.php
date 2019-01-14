<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function testHomepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}