<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspect;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    use ElasticquentTrait; use Aspect;
}