<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use App\Models\Aspect;

class Trim extends BaseModel
{
    public $keywords = ['name', 'make', 'model', 'framework', 'fuel', 'gearbox_type'];
    public $integers = ['model_id', 'votes', 'number_of_doors', 'number_of_seats', 'number_of_gears', 'year_begin',
        'year_end', 'fueltank_capacity', 'engine_power', 'max_trunk_capacity', 'max_speed', 'full_weight'];
    public $doubles = ['price', 'engine_capacity', 'acceleration', 'fuel_consumption'];

    public function __construct()
    {
        foreach (Aspect::getAspects() as $aspect) {
            $this->doubles[] =$aspect;
        }
    }
}