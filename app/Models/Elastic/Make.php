<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    use ElasticquentTrait;

    public function getIndexName(): string
    {
        return 'makes';
    }
}