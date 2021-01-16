<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Tests\FeatureTestCase;

class NotFoundTest extends FeatureTestCase
{
    public function test404page()
    {
        $response = $this->get('/notExisting');

        $response->assertStatus(404);

        $response->assertSee('The requested page does not exist.');
    }
}
