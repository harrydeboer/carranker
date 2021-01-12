<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elastic;

use App\Models\Elastic\Make;
use App\Models\Trim;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TrimTest extends TestCase
{
    use DatabaseMigrations;

    public function testElasticModelSyncEloquent()
    {
        $trimEloquent = Trim::factory()->create();
        $trimElastic = new \App\Models\Elastic\Trim();

        $propertiesElastic = array_merge($trimElastic->keywords, $trimElastic->doubles, $trimElastic->integers);
        foreach ($trimEloquent->getAttributes() as $key => $attribute) {
            if ($key !== 'id') {
                $this->assertTrue(in_array($key, $propertiesElastic));
            }
        }

        foreach ($propertiesElastic as $property) {
            $this->assertTrue(key_exists($property, $trimEloquent->getAttributes()));
        }
    }

    public function testMappings()
    {
        $makeElastic = new Make();

        $this->assertIsArray($makeElastic->getMappings());
    }
}
