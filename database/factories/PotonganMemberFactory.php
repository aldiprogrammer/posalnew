<?php

namespace Database\Factories;

use App\Models\PotonganMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class PotonganMemberFactory extends Factory
{
    protected $model = PotonganMember::class;

    public function definition(): array
    {
        return [
            'jenis' => fake()->randomElement(['diskon', 'rupiah']),
            'nominal' => fake()->numberBetween(5, 50),
            'tanggal_mulai' => fake()->dateTimeBetween('now', '+1 month'),
            'tanggal_akhir' => fake()->dateTimeBetween('+2 months', '+6 months'),
        ];
    }
}
