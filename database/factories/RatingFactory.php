<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use App\Models\Aspect;

$factory->define(App\Models\Rating::class, function (Faker $faker): array
{
    $user = factory('App\User')->create();
    $trim = factory('App\Models\Trim')->create();

    $array = [
        'user_id' => $user->getId(),
        'model_id' => $trim->getModel()->getId(),
        'trim_id' => $trim->getId(),
        'time' => time(),
        'content' => $faker->text(),
    ];

    foreach (Aspect::getAspects() as $aspect) {
        $array[$aspect] = $faker->randomNumber(1) + 1;
    }

    return $array;
});