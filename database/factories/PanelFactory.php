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
            'label' => $this->faker->word(),
            'order_column' => $this->faker->randomNumber(),
        ];
    }
}
