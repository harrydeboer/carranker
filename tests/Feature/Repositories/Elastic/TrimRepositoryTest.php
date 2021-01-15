<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Elastic;

use App\CarSpecs;
use App\Models\Aspects;
use App\Models\Trim;
use App\Repositories\Elastic\TrimRepository;
use Tests\FeatureTestCase;

class TrimRepositoryTest extends FeatureTestCase
{
    private TrimRepository $trimRepository;
    private \App\Models\Elastic\Trim $trim;

    protected function setUp(): void
    {
        parent::setUp();
        $this->trimRepository = $this->app->make(TrimRepository::class);
        $trimEloquent = Trim::factory()->create();
        $this->artisan('process:queue');
        $this->trim = $this->trimRepository->get($trimEloquent->getId());
    }

    public function testFindSelectedGeneration()
    {
        $generation = $this->trimRepository->findSelectedGeneration($this->trim->getId());

        $this->assertEquals($generation, $this->trim->getYearBegin() . '-' . $this->trim->getYearEnd());
    }

    public function testFindTrimsForSearch()
    {
        $trimWithName = Trim::factory()->create(['name' => 'testTrimRepo']);
        $this->artisan('process:queue')->execute();

        $trimCollection = $this->trimRepository->findForSearch($trimWithName->getName());

        $trim = $trimCollection->first();
        $this->assertEquals($trim->getName(), $trimWithName->getName());
        $this->assertEquals($trim->getId(), $trimWithName->getId());
    }

    public function testFindTrimsOfTop()
    {
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 6000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 7000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Sedan', 'price' => 11000]);
        Trim::factory()->create(['votes' => 31, 'framework' => 'Van']);
        Trim::factory()->create(['votes' => 25]);
        $this->artisan('process:queue')->execute();

        $index = '0';
        $framework = CarSpecs::specsChoice()['framework']['choices'][(int) $index];
        $formData = [];

        $aspects = [];
        foreach (Aspects::getAspects() as $aspect) {
            $aspects[$aspect] = '1';
        }
        $formData['aspects'] = $aspects;

        $formData['specsChoice'] = ['framework' . $index => 'on'];

        $specsRange = [];
        foreach (CarSpecs::specsRange() as $specName => $spec) {
            $specsRange[$specName . 'Min'] = null;
            $specsRange[$specName . 'Max'] = null;
        }
        $specsRange['priceMin'] = '5000';
        $specsRange['priceMax'] = '10000';
        $formData['specsRange'] = $specsRange;
        $minNumVotes = 30;
        $lengthTopTable = 4;

        $trims = $this->trimRepository->findTrimsOfTop($formData, $minNumVotes, $lengthTopTable);

        foreach ($trims as $trim) {
            $this->assertTrue((int) $trim->getVotes() >= $minNumVotes);
            $this->assertTrue($trim->getFramework() === $framework);
        }

        $this->assertCount(2, $trims);
    }
}
