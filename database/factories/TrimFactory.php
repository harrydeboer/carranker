<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Trim::class, function (Faker $faker): array {

    $model = factory('App\Models\Model')->create();

    return [
        'name' => $model->getName(),
        'model_id' => $model->getId(),
        'make' => $model->getMakename(),
        'model' => $model->getName(),
        'design' => 8,
        'comfort' => 8,
        'reliability' => 8,
        'performance' => 8,
        'framework' => 'Sedan',
        'fuel' => 'Gasoline',
        'number_of_doors' => 4,
        'number_of_seats' => 4,
        'number_of_gears' => 5,
        'gearbox_type' => 'Manual',
        'costs' => 8,
        'price' => 1000,
        'votes' => 31,
        'year_begin' => 2010,
        'year_end' => 2012,
        'max_trunk_capacity' => 100,
        'engine_capacity' => 10,
        'fueltank_capacity' => 50,
        'max_speed' => 200,
        'full_weight' => 1000,
        'engine_power' => 60,
        'acceleration' => 10,
        'fuel_consumption' => 5,
    ];
});