<?php

declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(App\Models\Profanity::class, function (Faker $faker): array {

    $name = $faker->unique()->name;

    return [
        'name' => strtolower(str_replace(' ', '', $name)),
    ];
});