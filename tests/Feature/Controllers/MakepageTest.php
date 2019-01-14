<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Repositories\MakeRepository;
use Tests\TestCase;

class MakepageTest extends TestCase
{
    public function testMakepage()
    {
        $makeRepository = new MakeRepository();
        $make = $makeRepository->get(1);
        $response = $this->get('/make/' . $make->getName());

        $response->assertStatus(200);
    }
}