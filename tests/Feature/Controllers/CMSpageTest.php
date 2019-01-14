<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class CMSpageTest extends TestCase
{
    public function testCMSpage()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }
}