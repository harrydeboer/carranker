<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    use ElasticquentTrait;

    public function getIndexName()
    {
        return 'makes';
    }

    protected $mappingProperties = [
        'name' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'content' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'wiki_car_make' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
    ];
}