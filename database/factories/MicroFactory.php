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
            'name' => str($this->faker->word())->upper()->toString(),
            'label' => $this->faker->word(),
            'order_column' => $this->faker->randomNumber(),
        ];
    }
}
