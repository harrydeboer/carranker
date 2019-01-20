<?php

declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(App\Models\Make::class, function (Faker $faker): array {

    return [
        'name' => $faker->unique()->name,
        'content' => $faker->text(),
        'wiki_car_make' => $faker->unique()->name,
    ];
});