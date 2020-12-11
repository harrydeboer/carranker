<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Profanity;
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
    public function definition()
    {
        $name = $this->faker->unique()->name;

        return [
            'name' => strtolower(str_replace(' ', '', $name)),
        ];
    }
}
