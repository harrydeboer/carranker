<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function testHomepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testFilterTop()
    {
        $response = $this->get('/filterTop');

        $response->assertStatus(200);
    }

    public function testShowMoreTopTable()
    {
        $response = $this->get('/showMoreTopTable/20/10');

        $response->assertStatus(200);
    }
}
