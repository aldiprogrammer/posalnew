<?php

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PenggunaApiTest extends TestCase
{
    use RefreshDatabase;

    private function dataValid(array $overrides = []): array
    {
        $jabatan = Jabatan::create(['nama' => 'Kasir']);

        return array_merge([
            'nama' => 'Budi',
            'username' => 'budi',
            'jabatan_id' => $jabatan->id,
            'password' => 'rahasia123',
        ], $overrides);
    }

    public function test_bisa_mengambil_daftar_pengguna_dengan_jabatan(): void
    {
        Pengguna::create($this->dataValid());

        $response = $this->getJson('/api/pengguna')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'Budi')
            ->assertJsonPath('data.0.jabatan.nama', 'Kasir');

        $this->assertArrayHasKey('password', $response->json('data.0'));
    }

    public function test_bisa_menambahkan_pengguna_dan_password_terhash(): void
    {
        $response = $this->postJson('/api/pengguna', $this->dataValid())
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.username', 'budi')
            ->assertJsonMissing(['password' => 'rahasia123']);

        $this->assertArrayHasKey('password', $response->json('data'));

        $pengguna = Pengguna::where('username', 'budi')->first();
        $this->assertTrue(Hash::check('rahasia123', $pengguna->password));
    }

    public function test_validasi_username_wajib_dan_unik(): void
    {
        Pengguna::create($this->dataValid());

        $this->postJson('/api/pengguna', $this->dataValid(['nama' => 'Siti']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['username']);

        $this->postJson('/api/pengguna', $this->dataValid(['username' => 'budi santoso']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_bisa_mengubah_pengguna_tanpa_mengubah_password(): void
    {
        $pengguna = Pengguna::create($this->dataValid());
        $passwordLama = $pengguna->password;

        $this->putJson("/api/pengguna/{$pengguna->id}", $this->dataValid([
            'nama' => 'Budi Santoso',
            'password' => null,
        ]))
            ->assertOk()
            ->assertJsonPath('data.nama', 'Budi Santoso');

        $pengguna->refresh();
        $this->assertEquals($passwordLama, $pengguna->password);
    }

    public function test_bisa_mengubah_password_pengguna(): void
    {
        $pengguna = Pengguna::create($this->dataValid());

        $this->putJson("/api/pengguna/{$pengguna->id}", $this->dataValid([
            'password' => 'passwordbaru',
        ]))
            ->assertOk();

        $this->assertTrue(Hash::check('passwordbaru', $pengguna->fresh()->password));
    }

    public function test_bisa_menghapus_pengguna(): void
    {
        $pengguna = Pengguna::create($this->dataValid());

        $this->deleteJson("/api/pengguna/{$pengguna->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('pengguna', ['id' => $pengguna->id]);
    }
}
