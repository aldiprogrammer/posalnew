<?php

namespace Tests\Feature;

use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_mengambil_daftar_kategori(): void
    {
        Kategori::create(['nama' => 'Makanan']);
        Kategori::create(['nama' => 'Minuman']);

        $this->getJson('/api/kategori')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['nama' => 'Makanan']);
    }

    public function test_bisa_menambahkan_kategori(): void
    {
        $this->postJson('/api/kategori', [
            'nama' => 'Snack',
            'keterangan' => 'Aneka camilan',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama', 'Snack');

        $this->assertDatabaseHas('kategori', ['nama' => 'Snack']);
    }

    public function test_validasi_nama_wajib_dan_unik(): void
    {
        Kategori::create(['nama' => 'Makanan']);

        $this->postJson('/api/kategori', ['nama' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nama']);

        $this->postJson('/api/kategori', ['nama' => 'Makanan'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nama']);
    }

    public function test_bisa_melihat_detail_kategori(): void
    {
        $kategori = Kategori::create(['nama' => 'Makanan']);

        $this->getJson("/api/kategori/{$kategori->id}")
            ->assertOk()
            ->assertJsonPath('data.nama', 'Makanan');
    }

    public function test_bisa_mengubah_kategori(): void
    {
        $kategori = Kategori::create(['nama' => 'Makanan']);

        $this->putJson("/api/kategori/{$kategori->id}", ['nama' => 'Makanan Berat'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama', 'Makanan Berat');
    }

    public function test_bisa_menghapus_kategori(): void
    {
        $kategori = Kategori::create(['nama' => 'Makanan']);

        $this->deleteJson("/api/kategori/{$kategori->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('kategori', ['id' => $kategori->id]);
    }
}
