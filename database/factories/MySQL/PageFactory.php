<?php

declare(strict_types=1);

namespace Database\Factories\MySQL;

use App\Models\MySQL\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {

        return [
            'name' => $this->faker->unique()->name,
            'title' => $this->faker->unique()->name,
            'content' => $this->faker->text(),
        ];
    }
}
