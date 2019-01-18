<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Model::class, function (Faker $faker): array {

    $make = factory('App\Models\Make')->create();

    return [
        'name' => $faker->unique()->name,
        'make_id' => $make->getId(),
        'make' => $make->getName(),
        'content' => $faker->text(),
        'wiki_car_model' => $faker->unique()->name,
        'design' => 8,
        'comfort' => 8,
        'reliability' => 8,
        'performance' => 8,
        'costs' => 8,
        'price' => 1000,
        'votes' => 31,
    ];
});