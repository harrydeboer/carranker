<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\FeatureTestCase;

class HomePageTest extends FeatureTestCase
{
    public function testHomepage()
    {
        $response = $this->get('/');

        $response->assertHeader('content-security-policy');
        $response->assertStatus(200);
    }

    public function testFilterTop()
    {
        $response = $this->get('/filterTop');

        $response->assertStatus(302);
    }

    public function testShowMoreTopTable()
    {
        $response = $this->get('/showMoreTopTable');

        $response->assertStatus(302);
    }
}
