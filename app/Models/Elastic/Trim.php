<?php

declare(strict_types=1);

namespace App\Models\Elastic;

class Trim extends Base
{
    public function getIndexName()
    {
        return 'trims';
    }

    protected $mappingProperties = [
        'model_id' => [
            'type' => 'integer',
        ],
        'name' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'make' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'model' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'price' => [
            'type' => 'double',
        ],
        'votes' => [
            'type' => 'integer',
        ],
        'year_begin' => [
            'type' => 'integer',
        ],
        'year_end' => [
            'type' => 'integer',
        ],
        'framework' => [
            'type' => 'text',
            'analyzer' => 'standard',
        ],
        'fuel' => [
            'type' => 'text',
            'analyzer' => 'standard',
        ],
        'number_of_doors' => [
            'type' => 'integer',
        ],
        'number_of_seats' => [
            'type' => 'integer',
        ],
        'number_of_gears' => [
            'type' => 'integer',
        ],
        'gearbox_type' => [
            'type' => 'text',
            'analyzer' => 'standard',
        ],
        'max_trunk_capacity' => [
            'type' => 'integer',
        ],
        'engine_capacity' => [
            'type' => 'double',
        ],
        'fueltank_capacity' => [
            'type' => 'integer',
        ],
        'max_speed' => [
            'type' => 'integer',
        ],
        'full_weight' => [
            'type' => 'integer',
        ],
        'engine_power' => [
            'type' => 'integer',
        ],
        'acceleration' => [
            'type' => 'double',
        ],
        'fuel_consumption' => [
            'type' => 'double',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        foreach (self::$aspects as $aspect) {
            $this->mappingProperties[$aspect] = ['type' => 'double'];
        }
        parent::__construct($attributes);
    }
}