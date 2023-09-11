<?php

namespace Database\Factories;

use App\Models\UnrecognizedMicro;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UnrecognizedMicroFactory extends Factory
{
    protected $model = UnrecognizedMicro::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
