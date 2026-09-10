<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        return [
            'kode_produk' => 'PRD-'.strtoupper(fake()->unique()->bothify('#####')),
            'kategori_id' => Kategori::factory(),
            'nama' => fake()->unique()->words(2, true),
            'keterangan' => fake()->sentence(),
            'harga' => fake()->numberBetween(1000, 500000),
            'diskon' => 0,
            'stok' => 'tersedia',
        ];
    }
}
