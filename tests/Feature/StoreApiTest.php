<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_mengambil_jenis_usaha_per_store(): void
    {
        $user = User::create([
            'name' => 'Warung Pak Budi',
            'username' => 'wpbuddi',
            'email' => 'budi@example.com',
            'password' => 'password',
            'nama_store' => 'Warung Pak Budi',
            'jenis_usaha' => 'Toko',
        ]);

        $this->getJson("/api/jenis-usaha/{$user->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id_store', (string) $user->id)
            ->assertJsonPath('data.nama_store', 'Warung Pak Budi')
            ->assertJsonPath('data.jenis_usaha', 'Toko');
    }

    public function test_jenis_usaha_cafe(): void
    {
        $user = User::create([
            'name' => 'Kopi Senja',
            'username' => 'kopisenja',
            'email' => 'kopi@example.com',
            'password' => 'password',
            'nama_store' => 'Kopi Senja',
            'jenis_usaha' => 'Cafe',
        ]);

        $this->getJson("/api/jenis-usaha/store/{$user->id}")
            ->assertOk()
            ->assertJsonPath('data.jenis_usaha', 'Cafe');
    }

    public function test_store_tidak_ditemukan(): void
    {
        $this->getJson('/api/jenis-usaha/999999')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
