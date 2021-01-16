<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Elastic;

use Tests\TestCase;
use App\Models\BaseModel as EloquentModel;
use App\Models\Elastic\BaseModel as ElasticModel;
use ReflectionClass;

class BaseModel extends TestCase
{
    public function elasticModelSyncEloquent(EloquentModel $eloquentModel, ElasticModel $elasticModel)
    {
        $propertiesElastic = $elasticModel->getMappingFields();
        foreach ($eloquentModel->getAttributes() as $key => $attribute) {
            if ($key !== 'id') {
                $this->assertTrue(in_array($key, $propertiesElastic));
            }
        }

        foreach ($propertiesElastic as $property) {
            $this->assertTrue(key_exists($property, $eloquentModel->getAttributes()));
        }

        $this->assertIsArray($elasticModel->getMappings());

        foreach ($elasticModel->getMappingFields() as $field) {
            $this->assertClassHasAttribute($field, get_class($elasticModel));
        }

        $reflectionClass = new ReflectionClass(get_class($elasticModel));

        foreach ($elasticModel->getKeywords() as $field) {
            $property = $reflectionClass->getProperty($field);
            $this->assertEquals('string', $property->getType()->getName());
        }

        foreach ($elasticModel->getBooleans() as $field) {
            $property = $reflectionClass->getProperty($field);
            $this->assertEquals('bool', $property->getType()->getName());
        }

        foreach ($elasticModel->getDoubles() as $field) {
            $property = $reflectionClass->getProperty($field);
            $this->assertEquals('float', $property->getType()->getName());
        }

        foreach ($elasticModel->getIntegers() as $field) {
            $property = $reflectionClass->getProperty($field);
            $this->assertEquals('int', $property->getType()->getName());
        }

        foreach ($elasticModel->getTexts() as $field) {
            $property = $reflectionClass->getProperty($field);
            $this->assertEquals('string', $property->getType()->getName());
        }
    }
}
