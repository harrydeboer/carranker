<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use App\Models\Aspect;

$factory->define(App\Models\Model::class, function (Faker $faker): array {

    $make = factory('App\Models\Make')->create();

    $array =  [
        'name' => str_replace(' ', '', $faker->unique()->name),
        'make_id' => $make->getId(),
        'make' => $make->getName(),
        'content' => $faker->text(),
        'wiki_car_model' => $faker->unique()->name,
        'price' => $faker->randomNumber(4),
        'votes' => $faker->randomNumber(2) + 1,
    ];

    foreach (Aspect::getAspects() as $aspect) {
        $array[$aspect] = $faker->randomNumber(1) + 1;
    }

    return $array;
});