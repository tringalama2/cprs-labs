<?php

namespace Database\Factories;

use App\Models\Micro;
use Illuminate\Database\Eloquent\Factories\Factory;

class MicroFactory extends Factory
{
    protected $model = Micro::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'label' => $this->faker->word(),
            'order_column' => $this->faker->randomNumber(),
        ];
    }
}
