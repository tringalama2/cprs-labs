<?php

namespace Database\Factories;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabFactory extends Factory
{
    protected $model = Lab::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'label' => fake()->word(),
            'panel_id' => fake()->randomNumber(),
            'order_column' => fake()->randomNumber(),
        ];
    }
}
