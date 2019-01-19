<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspect;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use ElasticquentTrait;

    public function getIndexName(): string
    {
        return 'models';
    }
}