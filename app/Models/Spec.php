<?php

declare(strict_types=1);

namespace App\Models;

trait Spec
{
    protected static $specsChoice = ['framework', 'fuel', 'number_of_doors',
        'number_of_seats', 'number_of_gears', 'gearbox_type'];
    protected static $specsRange = ['max_trunk_capacity', 'engine_capacity', 'fueltank_capacity',
        'max_speed', 'full_weight', 'engine_power', 'acceleration', 'fuel_consumption'];
}