<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\MySQL\Trim;
use Tests\FeatureTestCase;

class SearchTest extends FeatureTestCase
{
    public function testSearch()
    {
        $trim = Trim::factory()->create(['name' => 'testSearch']);
        $this->artisan('process:queue')->execute();

        $model = $trim->getModel();
        $make = $model->getMake();

        $this->artisan('process:queue')->execute();

        $response = $this->get('/search?query=' . $make->getName());
        $response->assertStatus(200);
        $response->assertSee('<h3>Makes</h3>', false);

        $response = $this->get('/search?query=' . $model->getName());
        $response->assertStatus(200);
        $response->assertSee('<h3>Models</h3>', false);

        $response = $this->get('/search?query=' . $trim->getName());
        $response->assertStatus(200);
        $response->assertSee('<h3>Trims</h3>', false);
    }
}
