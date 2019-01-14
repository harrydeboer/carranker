<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Make::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->name,
        'content' => $faker->text(),
        'wiki_car_make' => $faker->unique()->name,
    ];
});