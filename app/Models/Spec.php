<?php

declare(strict_types=1);

namespace App\Models;

/** The specs in this trait are used in the Trim model and are in sync with the CarSpecs class. */
trait Spec
{
    protected static array $specsChoice = ['framework', 'fuel', 'number_of_doors',
        'number_of_seats', 'number_of_gears', 'gearbox_type'];
    protected static array $specsRange = ['max_trunk_capacity', 'engine_capacity', 'fueltank_capacity',
        'max_speed', 'full_weight', 'engine_power', 'acceleration', 'fuel_consumption'];
}