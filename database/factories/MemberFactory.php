<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nik' => fake()->unique()->numerify('##############'),
            'alamat' => fake()->address(),
            'tanggal_bergabung' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
