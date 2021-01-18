<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Trim;
use App\Services\TrimService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetGenerationsSeriesTrims()
    {
        $trim = Trim::factory()->create(['name' => null]);

        $trimService = new TrimService();
        $generationSeriesTrims = $trimService->getGenerationsSeriesTrims([$trim]);
        $this->assertEquals($generationSeriesTrims[$trim->getYearBegin() . '-' . $trim->getYearEnd()]
                            [$trim->getFramework()][0], $trim->getId());

        $trim = Trim::factory()->create(['name' => 'notnull']);

        $generationSeriesTrims = $trimService->getGenerationsSeriesTrims([$trim]);
        $this->assertEquals(
            $generationSeriesTrims[
                $trim->getYearBegin() . '-' . $trim->getYearEnd()
        ][$trim->getFramework()][$trim->getName()],
            $trim->getId());
    }
}
