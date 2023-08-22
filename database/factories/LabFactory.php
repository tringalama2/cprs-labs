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
            'name' => $this->faker->name(),
            'label' => $this->faker->word(),
            'panel_id' => $this->faker->randomNumber(),
            'sort_id' => $this->faker->randomNumber(),
        ];
    }
}
