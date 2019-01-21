<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Trim;
use Tests\TestCase;

class SearchTest extends TestCase
{
    public function testSearch()
    {
        $trim = factory(Trim::class)->create();
        $model = $trim->getModel();
        $make = $model->getMake();

        $response = $this->get('/search?query=' . $make->getName());
        $response->assertStatus(200);
        $response->assertSee('<h3>Makes</h3>');

        $response = $this->get('/search?query=' . $model->getName());
        $response->assertStatus(200);
        $response->assertSee('<h3>Models</h3>');

        $response = $this->get('/search?query=' . $trim->getName());
        $response->assertStatus(200);
        $response->assertSee('<h3>Modelversions</h3>');
    }
}