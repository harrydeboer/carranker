<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Elastic;

use App\Models\Elastic\Trim;
use App\Forms\FilterTopForm;
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
        $generation = $this->trimRepository->findSelectedGeneration((string) $this->trim->getId());

        $this->assertEquals($generation, $this->trim->getYearBegin() . '-' . $this->trim->getYearEnd());
    }

    public function testFindTrimsForSearch()
    {
        $trimCollection = $this->trimRepository->findForSearch($this->trim->getName());

        $trim = $trimCollection->first();
        $this->assertEquals($trim->getName(), $this->trim->getName());
        $this->assertEquals($trim->getId(), $this->trim->getId());
    }

    public function testFindTrimsOfTop()
    {
        $index = '0';
        $framework = \App\CarSpecs::specsChoice()['framework']['choices'][(int) $index];

        $form = new FilterTopForm();
        $form->hasRequest = true;

        $aspects = [];
        foreach (\App\Models\Aspect::getAspects() as $aspect) {
            $aspects[$aspect] = '1';
        }
        $form->aspects = $aspects;

        $form->specsChoice = ['framework' . $index => '1'];

        $specsRange = [];
        foreach (\App\CarSpecs::specsRange() as $specName => $spec) {
            $specsRange[$specName . 'min'] = null;
            $specsRange[$specName . 'max'] = null;
        }
        $specsRange['pricemin'] = '5000';
        $specsRange['pricemax'] = '10000';
        $form->specsRange = $specsRange;
        $minNumVotes = 30;
        $lengthTopTable = 4;

        $trims = $this->trimRepository->findTrimsOfTop($form, $minNumVotes, $lengthTopTable);

        foreach ($trims as $trim) {
            $this->assertTrue((int) $trim->votes >= $minNumVotes);
            $this->assertTrue($trim->getFramework() === $framework);
        }

        $this->assertEquals(count($trims), 2);
    }
}
