<?php

declare(strict_types=1);

use App\Providers\WPHasher;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\User::class, function (Faker $faker): array
{
    $hasher = new WPHasher(app());
    $userName = $faker->name;

    return [
        'user_login' => $userName,
        'user_email' => $faker->unique()->safeEmail,
        'user_pass' => $hasher->make($faker->name),
        'user_nicename' => $userName,
        'user_url' => "",
        'user_activation_key' => "",
        'user_status' => 0,
        'display_name' => $userName,
        'user_registered' => $faker->time('Y-m-d H:i:s'),
        'remember_token' => Str::random(10),
    ];
});
