<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Repositories\MakeRepository;
use Tests\TestCase;

class MakePageTest extends TestCase
{
    private MakeRepository $makeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->makeRepository = $this->app->make(MakeRepository::class);
    }

    public function testMakepage()
    {
        $make = $this->makeRepository->get(1);
        $response = $this->get('/make/' . $make->getName());

        $response->assertStatus(200);
    }

    public function test404()
    {
        $response = $this->get('/make/doesnotexist');

        $response->assertStatus(404);
    }
}
