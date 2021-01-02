<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class CMSPagesTest extends TestCase
{
    public function testAbout()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    public function test404()
    {
        $response = $this->get('/doesnotexist');

        $response->assertStatus(404);
    }

	public function testAuth()
	{
		$response = $this->get('/auth');

		$response->assertStatus(200);
	}

	public function testRegister()
	{
		$response = $this->get('/register');

		$response->assertStatus(200);
	}
}
