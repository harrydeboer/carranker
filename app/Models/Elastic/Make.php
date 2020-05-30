<?php

declare(strict_types=1);

namespace App\Models\Elastic;

class Make extends BaseModel
{
    public $keywords = ['name', 'wiki_car_make'];
    public $texts = ['content'];
}