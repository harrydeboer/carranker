<?php

declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(App\Models\Page::class, function (Faker $faker): array {

    return [
        'name' => $faker->unique()->name,
        'title' => $faker->unique()->name,
        'content' => $faker->text(),
    ];
});