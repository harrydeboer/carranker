<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Providers\WPHasher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $hasher = new WPHasher(app());
        $userName = $this->faker->name;

        return [
            'user_login' => $userName,
            'user_email' => $this->faker->unique()->safeEmail,
            'user_pass' => $hasher->make($this->faker->name),
            'user_nicename' => $userName,
            'user_url' => "",
            'user_activation_key' => "",
            'user_status' => 0,
            'display_name' => $userName,
            'user_registered' => $this->faker->time('Y-m-d H:i:s'),
            'remember_token' => Str::random(10),
            'email_verified_at' => $this->faker->time('Y-m-d H:i:s'),
        ];
    }
}
