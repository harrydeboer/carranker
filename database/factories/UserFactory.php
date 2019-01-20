<?php

declare(strict_types=1);

use App\Providers\WPHasher;
use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker): array {
    static $password;
    $hasher = new WPHasher(app());
    $userName = $faker->name;
    return [
        'user_login' => $userName,
        'user_email' => $faker->unique()->safeEmail,
        'user_pass' => $password ?: $password = $hasher->make('secret'),
        'user_nicename' => $userName,
        'user_url' => "",
        'user_activation_key' => "",
        'user_status' => 0,
        'display_name' => $userName,
        'user_registered' => $faker->time('Y-m-d H:i:s'),
        'remember_token' => str_random(10),
    ];
});
