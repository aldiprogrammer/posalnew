<?php

namespace Database\Factories;

use App\Models\Meja;
use Illuminate\Database\Eloquent\Factories\Factory;

class MejaFactory extends Factory
{
    protected $model = Meja::class;

    public function definition(): array
    {
        return [
            'no_meja' => fake()->unique()->numberBetween(1, 50),
            'tanggal' => fake()->date(),
        ];
    }
}
