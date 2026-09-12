<?php

namespace Database\Factories;

use App\Models\Inventaris;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inventaris>
 */
class InventarisFactory extends Factory
{
    protected $model = Inventaris::class;

    public function definition(): array
    {
        return [
            'kode_barang' => fake()->unique()->bothify('BRG-####'),
            'nama_barang' => fake()->words(2, true),
            'kondisi_barang' => fake()->randomElement(['Baik', 'Rusak Ringan', 'Rusak Berat']),
            'tgl_masuk' => fake()->date(),
        ];
    }
}
