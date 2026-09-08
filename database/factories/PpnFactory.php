<?php

namespace Database\Factories;

use App\Models\Ppn;
use Illuminate\Database\Eloquent\Factories\Factory;

class PpnFactory extends Factory
{
    protected $model = Ppn::class;

    public function definition(): array
    {
        return [
            'persentase' => fake()->randomElement([11, 12]),
            'aktif' => fake()->boolean(),
        ];
    }
}
