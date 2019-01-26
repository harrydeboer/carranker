<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Trim;
use App\Repositories\TrimRepository;
use App\Services\TrimService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetGenerationsSeriesTrims()
    {
        $trim = factory(Trim::class)->create(['name' => null]);

        $collection = new Collection();

        $collection->add($trim);

        $trimService = new TrimService();
        $generationSeriesTrims = $trimService->getGenerationsSeriesTrims($collection);
        $this->assertEquals($generationSeriesTrims[$trim->getYearBegin() . '-' . $trim->getYearEnd()][$trim->getFramework()][0], $trim->getId());

        $trim = factory(Trim::class)->create(['name' => 'notnull']);

        $collection = new Collection();

        $collection->add($trim);

        $generationSeriesTrims = $trimService->getGenerationsSeriesTrims($collection);
        $this->assertEquals($generationSeriesTrims[$trim->getYearBegin() . '-' . $trim->getYearEnd()][$trim->getFramework()][$trim->getName()], $trim->getId());
    }

    public function testHasTrimTypes()
    {
        $trim = factory(Trim::class)->create(['name' => null]);

        $collection = new Collection();

        $collection->add($trim);

        $trimService = new TrimService();
        $this->assertFalse($trimService->hasTrimTypes($collection));

        $trim = factory(Trim::class)->create(['name' => 'notnull']);

        $collection = new Collection();

        $collection->add($trim);

        $this->assertTrue($trimService->hasTrimTypes($collection));
    }
}