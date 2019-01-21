<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use App\Models\Aspect;

$factory->define(App\Models\Trim::class, function (Faker $faker): array {

    $model = factory('App\Models\Model')->create();

    $year_begin = $faker->randomNumber(4);

    $array = [
        'name' => $faker->name,
        'model_id' => $model->getId(),
        'make' => $model->getMakename(),
        'model' => $model->getName(),
        'framework' => $faker->randomElement(\App\CarSpecs::specsChoice()['framework']['choices']),
        'fuel' => $faker->randomElement(\App\CarSpecs::specsChoice()['fuel']['choices']),
        'number_of_doors' => $faker->randomElement(\App\CarSpecs::specsChoice()['number_of_doors']['choices']),
        'number_of_seats' => $faker->randomElement(\App\CarSpecs::specsChoice()['number_of_seats']['choices']),
        'number_of_gears' => $faker->randomElement(\App\CarSpecs::specsChoice()['number_of_gears']['choices']),
        'gearbox_type' => $faker->randomElement(\App\CarSpecs::specsChoice()['gearbox_type']['choices']),
        'price' => $faker->randomNumber(4),
        'votes' => $faker->randomNumber(2),
        'year_begin' => $year_begin,
        'year_end' => $year_begin + $faker->randomNumber(1),
        'max_trunk_capacity' => $faker->randomNumber(2),
        'engine_capacity' => $faker->randomFloat(2,0,10),
        'fueltank_capacity' => $faker->randomNumber(2),
        'max_speed' => $faker->randomNumber(3),
        'full_weight' => $faker->randomNumber(4),
        'engine_power' => $faker->randomNumber(2),
        'acceleration' => $faker->randomFloat(2,0, 100),
        'fuel_consumption' => $faker->randomFloat(2, 0, 100),
    ];

    foreach (Aspect::getAspects() as $aspect) {
        $array[$aspect] = $faker->randomNumber(1) + 1;
    }

    return $array;
});