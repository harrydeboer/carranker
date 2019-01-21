<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class NotFoundTest extends TestCase
{
    public function test404page()
    {
        $response = $this->get('/notexisting');

        $response->assertStatus(404);

        $response->assertSee('<title>Error</title>');
    }
}