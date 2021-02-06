<?php

declare(strict_types=1);

namespace Database\Factories\MySQL;

use App\Models\MySQL\MailUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class MailUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MailUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'domain' => 'carranker.com',
            'password' => $this->faker->password,
            'email' => $this->faker->unique()->name,
            'forward' => $this->faker->unique()->name,
        ];
    }
}
