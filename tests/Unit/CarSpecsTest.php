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
        }

        $this->assertEquals($names, self::$specsChoice);
    }

    public function testSpecsRange()
    {
        $names = [];
        foreach (CarSpecs::specsRange() as $specname => $spec) {
            if ($specname !== 'price' && $specname !== 'generation') {
                $names[] = $specname;
            }
        }

        $this->assertEquals($names, self::$specsRange);
    }
}