<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Page::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->name,
        'title' => $faker->unique()->name,
        'content' => $faker->text(),
    ];
});