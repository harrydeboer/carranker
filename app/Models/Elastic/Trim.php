<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Trim extends Model
{
    use ElasticquentTrait;

    public function getIndexName()
    {
        return 'trims';
    }
}