<?php

declare(strict_types=1);

namespace Database\Factories\MySQL;

use App\Models\MySQL\Model;
use App\Models\MySQL\Make;
use App\Models\MySQL\Aspects;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $make = Make::factory()->create();

        $array = [
            'name' => str_replace(' ', '', $this->faker->unique()->name),
            'make_id' => $make->getId(),
            'make_name' => $make->getName(),
            'content' => $this->faker->text(),
            'wiki_car_model' => $this->faker->unique()->name,
            'price' => $this->faker->randomNumber(4),
            'votes' => $this->faker->randomNumber(2) + 1,
        ];

        foreach (Aspects::getAspects() as $aspect) {
            $array[$aspect] = $this->faker->randomNumber(1) + 1;
        }

        return $array;
    }
}
