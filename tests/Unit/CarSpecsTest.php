<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\CarSpecs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Spec;

class CarSpecsTest extends TestCase
{
    use Spec;
    use DatabaseMigrations;

    public function testSpecsChoice()
    {
        $names = [];
        foreach (CarSpecs::specsChoice() as $specname => $spec) {
            $names[] = $specname;
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
        foreach (CarSpecs::specsRange() as $specname => $spec) {
            $this->assertArrayHasKey('min', $spec);
            $this->assertArrayHasKey('max', $spec);
            $this->assertArrayHasKey('step', $spec);
            $this->assertArrayHasKey('display', $spec);
            $this->assertArrayHasKey('show', $spec);
            $this->assertArrayHasKey('unit', $spec);
            if ($specname !== 'price' && $specname !== 'generation') {
                $names[] = $specname;
            }
        }

        $this->assertEquals($names, self::$specsRange);
    }
}