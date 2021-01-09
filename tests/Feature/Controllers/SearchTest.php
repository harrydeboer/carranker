<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Trim;
use Tests\TestCase;

class SearchTest extends TestCase
{
    public function testSearch()
    {
        $trim = Trim::factory()->create(['name' => 'testSearch']);
        $this->artisan('processqueue')->execute();
        sleep(2);

        $model = $trim->getModel();
        $make = $model->getMake();

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
