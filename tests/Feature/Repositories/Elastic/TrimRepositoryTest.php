<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Elastic;

use App\Models\Elastic\Trim;
use App\Repositories\Elastic\TrimRepository;
use Tests\TestCase;

class TrimRepositoryTest extends TestCase
{
    private TrimRepository $trimRepository;
    private Trim $trim;

    public function setUp(): void
    {
        parent::setUp();
        $this->trimRepository = $this->app->make(TrimRepository::class);
        $this->trim = $this->trimRepository->get(1);
    }

    public function testFindSelectedGeneration()
    {
        $generation = $this->trimRepository->findSelectedGeneration($this->trim->getId());

        $this->assertEquals($generation, $this->trim->getYearBegin() . '-' . $this->trim->getYearEnd());
    }

    public function testFindTrimsForSearch()
    {
        $trimWithName = \App\Models\Trim::factory()->create(['name' => 'testTrimRepo']);
        $this->artisan('processqueue')->execute();
        sleep(2);
        $trimCollection = $this->trimRepository->findForSearch($trimWithName->getName());

        $trim = $trimCollection->first();
        $this->assertEquals($trim->getName(), $trimWithName->getName());
        $this->assertEquals($trim->getId(), $trimWithName->getId());
    }

    public function testFindTrimsOfTop()
    {
        $index = '0';
        $framework = \App\CarSpecs::specsChoice()['framework']['choices'][(int) $index];
        $formData = [];

        $aspects = [];
        foreach (\App\Models\Aspect::getAspects() as $aspect) {
            $aspects[$aspect] = '1';
        }
        $formData['aspects'] = $aspects;

        $formData['specsChoice'] = ['framework' . $index => 'on'];

        $specsRange = [];
        foreach (\App\CarSpecs::specsRange() as $specName => $spec) {
            $specsRange[$specName . 'min'] = null;
            $specsRange[$specName . 'max'] = null;
        }
        $specsRange['pricemin'] = '5000';
        $specsRange['pricemax'] = '10000';
        $formData['specsRange'] = $specsRange;
        $minNumVotes = 30;
        $lengthTopTable = 4;

        $trims = $this->trimRepository->findTrimsOfTop($formData, $minNumVotes, $lengthTopTable);

        foreach ($trims as $trim) {
            $this->assertTrue((int) $trim->votes >= $minNumVotes);
            $this->assertTrue($trim->getFramework() === $framework);
        }

        try {
            $this->assertEquals(count($trims), 2);
        } catch (\Exception $e) {
            $allTrims = $this->trimRepository->all();
            var_dump($allTrims);
        }
    }
}
