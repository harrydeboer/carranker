<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Models;

class ElasticJob extends BaseModel
{
    protected static $table = 'elastic_jobs';

    protected $id;
    protected $make_id;
    protected $model_id;
    protected $trim_id;
    protected $action;
}