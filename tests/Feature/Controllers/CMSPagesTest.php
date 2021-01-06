<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Page;

class CMSPagesTest extends TestCase
{
    public function testAbout()
    {
        $name = 'about';
        Page::factory()->create(['name' => $name]);
        $response = $this->get('/' . $name);

        $response->assertStatus(200);
    }

    public function test404()
    {
        $response = $this->get('/doesnotexist');

        $response->assertStatus(404);
    }
}
