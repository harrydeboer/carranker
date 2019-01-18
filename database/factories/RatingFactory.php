<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Rating::class, function (Faker $faker): array {

    return [
        'user_id' => 1,
        'model_id' => 1,
        'trim_id' => 1,
        'design' => 8,
        'comfort' => 8,
        'reliability' => 8,
        'performance' => 8,
        'costs' => 8,
        'time' => time(),
        'content' => $faker->text(),
    ];
});