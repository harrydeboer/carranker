<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspect;

class Model extends BaseModel
{
    public $keywords = ['name', 'make', 'wiki_car_model'];
    public $texts = ['content'];
    public $integers = ['make_id', 'votes'];
    public $doubles = ['price'];

    public function __construct()
    {
        foreach (Aspect::getAspects() as $aspect) {
            $this->doubles[] =$aspect;
        }
    }
}