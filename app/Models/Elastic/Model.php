<?php

declare(strict_types=1);

namespace App\Models\Elastic;

class Model extends Base
{
    public function getIndexName()
    {
        return 'models';
    }

    protected $mappingProperties = [
        'make_id' => [
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
        'content' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'wiki_car_model' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'price' => [
            'type' => 'double',
        ],
        'votes' => [
            'type' => 'integer',
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