<?php

namespace Database\Factories;

use App\Models\UnrecognizedLab;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnrecognizedLabFactory extends Factory
{
    protected $model = UnrecognizedLab::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
        ];
    }
}
