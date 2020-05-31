<?php

declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(App\Models\MailUser::class, function (Faker $faker): array {

    return [
        'domain' => 'carranker.com',
        'password' => $faker->password,
        'email' => $faker->unique()->name,
        'forward' => $faker->unique()->name,
    ];
});