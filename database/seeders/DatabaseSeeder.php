<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $jabatan = Jabatan::query()->firstOrCreate(['nama' => 'Administrator']);

        Pengguna::query()->firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Admin',
                'jabatan_id' => $jabatan->id,
                'password' => Hash::make('password'),
            ]
        );
    }
}
