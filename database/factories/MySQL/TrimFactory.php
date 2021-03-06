<?php

declare(strict_types=1);

namespace Database\Factories\MySQL;

use App\Models\MySQL\Trim;
use App\Models\MySQL\Model;
use App\Models\Traits\AspectsTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Parameters\CarSpecs;

class TrimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trim::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {

        $model = Model::factory()->create();

        $year_begin = $this->faker->randomNumber(4);

        $array = [
            'name' => null,
            'model_id' => $model->getId(),
            'make_name' => $model->getMakeName(),
            'model_name' => $model->getName(),
            'framework' => $this->faker->randomElement(CarSpecs::specsChoice()['framework']['choices']),
            'fuel' => $this->faker->randomElement(CarSpecs::specsChoice()['fuel']['choices']),
            'number_of_doors' => $this->faker->randomElement(CarSpecs::specsChoice()['number_of_doors']['choices']),
            'number_of_seats' => $this->faker->randomElement(CarSpecs::specsChoice()['number_of_seats']['choices']),
            'number_of_gears' => $this->faker->randomElement(CarSpecs::specsChoice()['number_of_gears']['choices']),
            'gearbox_type' => $this->faker->randomElement(CarSpecs::specsChoice()['gearbox_type']['choices']),
            'price' => $this->faker->randomNumber(4),
            'votes' => $this->faker->randomNumber(2) + 1,
            'year_begin' => $year_begin,
            'year_end' => $year_begin + $this->faker->randomNumber(1),
            'max_trunk_capacity' => $this->faker->randomNumber(2),
            'engine_capacity' => $this->faker->randomFloat(2, 0, 10),
            'fueltank_capacity' => $this->faker->randomNumber(2),
            'max_speed' => $this->faker->randomNumber(3),
            'full_weight' => $this->faker->randomNumber(4),
            'engine_power' => $this->faker->randomNumber(2),
            'acceleration' => $this->faker->randomFloat(2, 0, 100),
            'fuel_consumption' => $this->faker->randomFloat(2, 0, 100),
        ];

        foreach (AspectsTrait::getAspects() as $aspect) {
            $array[$aspect] = $this->faker->randomNumber(1) + 1;
        }

        return $array;
    }
}
