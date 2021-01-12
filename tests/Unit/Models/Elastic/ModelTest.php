<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elastic;

use App\Models\Elastic\Make;
use App\Models\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use DatabaseMigrations;

    public function testElasticModelSyncEloquent()
    {
        $modelEloquent = Model::factory()->create();
        $modelElastic = new \App\Models\Elastic\Model();

        $propertiesElastic = array_merge($modelElastic->keywords, $modelElastic->texts,
        $modelElastic->integers, $modelElastic->doubles);
        foreach ($modelEloquent->getAttributes() as $key => $attribute) {
            if ($key !== 'id') {
                $this->assertTrue(in_array($key, $propertiesElastic));
            }
        }

        foreach ($propertiesElastic as $property) {
            $this->assertTrue(key_exists($property, $modelEloquent->getAttributes()));
        }
    }

    public function testMappings()
    {
        $makeElastic = new Make();

        $this->assertIsArray($makeElastic->getMappings());
    }
}
