<?php

namespace Database\Factories;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'jabatan' => fake()->randomElement(['Kasir', 'Gudang', 'Manajer', 'Admin']),
            'no_wa' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
        ];
    }
}
