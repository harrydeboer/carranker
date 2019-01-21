<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class CMSpagesTest extends TestCase
{
    public function testAbout()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    public function testPHPinfo()
    {
        $response = $this->get('/phpinfo');

        $response->assertStatus(200);
    }

    public function testOPcacheReset()
    {
        $response = $this->get('/opcachereset');

        $response->assertStatus(200);
    }
}