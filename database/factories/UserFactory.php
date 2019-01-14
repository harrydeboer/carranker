<?php

use App\Providers\WPHasher;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
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
