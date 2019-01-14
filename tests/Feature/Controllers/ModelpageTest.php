<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Repositories\TrimRepository;
use Tests\TestCase;

class ModelpageTest extends TestCase
{
    public function testModelpage()
    {
        $trimRepository = new TrimRepository();
        $trim = $trimRepository->get(1);
        $response = $this->get('/model/' . $trim->getModel()->getMakename() . '/' . $trim->getModel()->getName());

        $response->assertStatus(200);
    }
}