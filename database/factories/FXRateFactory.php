<?php

use Faker\Generator as Faker;

$factory->define(App\Models\FXRate::class, function (Faker $faker): array {

    return [
        'name' => 'euro/dollar',
        'value' => $faker->randomFloat(4),
    ];
});