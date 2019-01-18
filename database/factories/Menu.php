<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Menu::class, function (Faker $faker): array {

    return [
        'name' => $faker->unique()->name,
    ];
});