<?php

declare(strict_types=1);

namespace App\Models\Elastic;

abstract class BaseModel
{
    public function getMappings(): array
    {
        $mappings = [
            '_source' => [
                'enabled' => true,
            ],
        ];

        if (isset($this->keywords)) {
            foreach ($this->keywords as $keyword) {
                $mappings['properties'][$keyword] = ['type' => 'keyword'];
            }
        }
        if (isset($this->texts)) {
            foreach ($this->texts as $text) {
                $mappings['properties'][$text] = ['type' => 'text'];
            }
        }
        if (isset($this->integers)) {
            foreach ($this->integers as $integer) {
                $mappings['properties'][$integer] = ['type' => 'integer'];
            }
        }
        if (isset($this->doubles)) {
            foreach ($this->doubles as $double) {
                $mappings['properties'][$double] = ['type' => 'double'];
            }
        }
        if (isset($this->timestamps)) {
            foreach ($this->timestamps as $timestamp) {
                $mappings['properties'][$timestamp] = ['type' => 'date'];
            }
        }
        if (isset($this->booleans)) {
            foreach ($this->booleans as $boolean) {
                $mappings['properties'][$boolean] = ['type' => 'boolean'];
            }
        }

        return $mappings;
    }
}