<?php

declare(strict_types=1);

use App\Models\Trim;
use App\Repositories\TrimRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrimRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $trimRepository;
    private $trim;

    public function setUp()
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
        factory(Trim::class)->create(['votes' => 31, 'framework' => $framework]);
        factory(Trim::class)->create(['votes' => 31, 'framework' => $framework]);
        factory(Trim::class)->create(['votes' => 31]);
        factory(Trim::class)->create(['votes' => 25]);

        $session = session();

        $aspects = [];
        foreach (\App\Models\Aspect::getAspects() as $aspect) {
            $aspects[$aspect] = '1';
        }
        $session->put('aspects', $aspects);

        $session->put('specsChoice', ['framework' . $index => '1']);
        $minNumVotes = 30;
        $lengthTopTable = 2;

        $trims = $this->trimRepository->findTrimsOfTop($session, $minNumVotes, $lengthTopTable);

        foreach ($trims as $trim) {
            $this->assertTrue((int) $trim->votes >= $minNumVotes);
            $this->assertTrue($trim->getFramework() === $framework);
        }

        $this->assertEquals(count($trims), $lengthTopTable);
    }
}