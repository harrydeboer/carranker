<?php

declare(strict_types=1);

namespace Tests\Unit\Parameters;

use App\Parameters\CarSpecs;
use App\Models\MySQL\Spec;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CarSpecsTest extends TestCase
{
    use Spec;
    use DatabaseMigrations;

    public function testSpecsChoice()
    {
        $names = [];
        foreach (CarSpecs::specsChoice() as $specName => $spec) {
            $names[] = $specName;
            $this->assertArrayHasKey('show', $spec);
            $this->assertArrayHasKey('display', $spec);
            $this->assertArrayHasKey('choices', $spec);
            $this->assertTrue(is_array($spec['choices']));
        }

        $this->assertEquals($names, self::$specsChoice);
    }

    public function testSpecsRange()
    {
        $names = [];
        foreach (CarSpecs::specsRange() as $specName => $spec) {
            $this->assertArrayHasKey('min', $spec);
            $this->assertArrayHasKey('max', $spec);
            $this->assertArrayHasKey('step', $spec);
            $this->assertArrayHasKey('display', $spec);
            $this->assertArrayHasKey('show', $spec);
            $this->assertArrayHasKey('unit', $spec);
            if ($specName !== 'price' && $specName !== 'generation') {
                $names[] = $specName;
            }
        }

        $this->assertEquals($names, self::$specsRange);
    }
}
