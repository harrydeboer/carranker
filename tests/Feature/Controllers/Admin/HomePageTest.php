<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Admin;

class HomePageTest extends LoginAdmin
{
    public function testHomePage()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }
}