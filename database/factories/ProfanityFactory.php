<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Profanity::class, function (Faker $faker): array {

    return [
        'name' => $faker->unique()->name,
    ];
});