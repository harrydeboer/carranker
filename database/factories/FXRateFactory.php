<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FXRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class FXRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FXRate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => 'euro/dollar',
            'value' => $this->faker->randomFloat(4),
        ];
    }
}
