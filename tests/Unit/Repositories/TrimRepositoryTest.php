<?php

declare(strict_types=1);

use App\Models\Trim;
use App\Forms\FilterTopForm;
use App\Repositories\TrimRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $trimRepository;
    private $trim;

    public function setUp(): void
    {
        parent::setUp();
        $this->trimRepository = new TrimRepository();
        $this->trim = factory(Trim::class)->create(['votes' => 1]);
    }

    public function testFindSelectedGeneration()
    {
        $trim = factory(Trim::class)->create();
        $generation = $this->trimRepository->findSelectedGeneration($trim);

        $this->assertEquals($generation, $trim->getYearBegin() . '-' . $trim->getYearEnd());
    }

    public function testFindTrimsForSearch()
    {
        $trimCollection = $this->trimRepository->findTrimsForSearch($this->trim->getName());

        foreach ($trimCollection as $trim) {
            $this->assertEquals($trim->getName(), $this->trim->getName());
            $this->assertEquals($trim->getId(), $this->trim->getId());
        }
    }

    public function testFindTrimsOfTop()
    {
        $index = '0';
        $framework = \App\CarSpecs::specsChoice()['framework']['choices'][(int) $index];
        factory(Trim::class)->create(['votes' => 31, 'framework' => $framework, 'price' => 6000]);
        factory(Trim::class)->create(['votes' => 31, 'framework' => $framework, 'price' => 7000]);
        factory(Trim::class)->create(['votes' => 31, 'framework' => $framework, 'price' => 11000]);
        factory(Trim::class)->create(['votes' => 31, 'framework' => 'Van']);
        factory(Trim::class)->create(['votes' => 25]);

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