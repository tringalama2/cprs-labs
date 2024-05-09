<?php

namespace Database\Factories;

use App\Models\Panel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PanelFactory extends Factory
{
    protected $model = Panel::class;

    public function definition()
    {
        return [
            'label' => fake()->word(),
            'order_column' => fake()->randomNumber(),
        ];
    }
}
