<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\MySQL\Make;
use App\Repositories\Interfaces\MakeRepositoryInterface;
use Tests\FeatureTestCase;

class MakePageTest extends FeatureTestCase
{
    private MakeRepositoryInterface $makeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeRepository = $this->app->make(MakeRepositoryInterface::class);
    }

    public function testMakePage()
    {
        $makeEloquent = Make::factory()->create();
        $this->artisan('process:queue')->execute();
        $make = $this->makeRepository->get($makeEloquent->getId());
        $response = $this->get('/make/' . $make->getName());

        $response->assertStatus(200);
    }

    public function test404()
    {
        $response = $this->get('/make/doesNotExist');

        $response->assertStatus(404);
    }
}
