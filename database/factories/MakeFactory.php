<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Make;
use Illuminate\Database\Eloquent\Factories\Factory;

class MakeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Make::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => str_replace(' ', '', $this->faker->unique()->name),
            'content' => $this->faker->text(),
            'wiki_car_make' => $this->faker->unique()->name,
        ];
    }
}
