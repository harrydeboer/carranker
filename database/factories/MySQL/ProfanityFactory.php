<?php

declare(strict_types=1);

namespace Database\Factories\MySQL;

use App\Models\MySQL\Profanity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfanityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profanity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->name;
        $name = strtolower(str_replace(' ', '', $name));
        $name = str_replace(',', '', $name);

        return [
            'name' => $name,
        ];
    }
}
