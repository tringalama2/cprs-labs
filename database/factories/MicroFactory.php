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
            'name' => str(fake()->word())->upper()->toString(),
            'label' => fake()->word(),
            'order_column' => fake()->randomNumber(),
        ];
    }
}
