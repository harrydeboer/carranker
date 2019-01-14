<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Profanity::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->name,
    ];
});