<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Repositories\Elastic\TrimRepository;
use Tests\TestCase;

class SearchTest extends TestCase
{
    private TrimRepository $trimRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->trimRepository = $this->app->make(TrimRepository::class);
    }

    public function testSearch()
    {
        $trim = $this->trimRepository->get(1);
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
        $response->assertSee('<h3>Model Versions</h3>', false);
    }
}
