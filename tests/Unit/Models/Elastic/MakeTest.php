<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elastic;

use App\Models\Make;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MakeTest extends TestCase
{
    use DatabaseMigrations;

    public function testElasticModelSyncEloquent()
    {
        $makeEloquent = factory(Make::class)->create();
        $makeElastic = new \App\Models\Elastic\Make();

        $propertiesElastic = array_merge($makeElastic->keywords, $makeElastic->texts);
        foreach ($makeEloquent->getAttributes() as $key => $attribute) {
            if ($key !== 'id') {
                $this->assertTrue(in_array($key, $propertiesElastic));
            }
        }

        foreach ($propertiesElastic as $property) {
            $this->assertTrue(key_exists($property, $makeEloquent->getAttributes()));
        }
    }

    public function testMappings()
    {
        $makeElastic = new \App\Models\Elastic\Make();

        $this->assertIsArray($makeElastic->getMappings());
    }
}