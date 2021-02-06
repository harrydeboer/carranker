<?php

declare(strict_types=1);

namespace Database\Factories\MySQL;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MySQL\Rating;
use App\Models\MySQL\Aspects;
use App\Models\MySQL\User;
use App\Models\MySQL\Trim;

class RatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $trim = Trim::factory()->create();

        $array = [
            'user_id' => $user->getId(),
            'model_id' => $trim->getModel()->getId(),
            'trim_id' => $trim->getId(),
            'time' => time(),
            'content' => $this->faker->text(),
            'pending' => 1,
        ];

        foreach (Aspects::getAspects() as $aspect) {
            $array[$aspect] = $this->faker->randomNumber(1) + 1;
        }

        return $array;
    }
}
