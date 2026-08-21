<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdukApiTest extends TestCase
{
    use RefreshDatabase;

    private function dataValid(array $overrides = []): array
    {
        $kategori = Kategori::create(['nama' => 'Makanan']);

        return array_merge([
            'kategori_id' => $kategori->id,
            'nama' => 'Nasi Goreng',
            'keterangan' => 'Favorit',
            'harga' => 15000,
            'diskon' => 0,
            'stok' => 'tersedia',
        ], $overrides);
    }

    public function test_bisa_mengambil_daftar_produk_dengan_kategori(): void
    {
        $produk = Produk::create($this->dataValid());

        $this->getJson('/api/produk')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'Nasi Goreng')
            ->assertJsonPath('data.0.kategori.nama', 'Makanan');
    }

    public function test_bisa_menambahkan_produk_dengan_foto(): void
    {
        Storage::fake('public');

        $this->post('/api/produk', array_merge($this->dataValid(), [
            'foto' => UploadedFile::fake()->image('nasi.png'),
        ]))
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama', 'Nasi Goreng');

        $produk = Produk::first();
        $this->assertNotNull($produk->foto);
        Storage::disk('public')->assertExists($produk->foto);
    }

    public function test_validasi_harga_wajib(): void
    {
        $this->postJson('/api/produk', $this->dataValid(['harga' => null]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['harga']);
    }

    public function test_bisa_mengubah_produk(): void
    {
        $produk = Produk::create($this->dataValid());

        $this->putJson("/api/produk/{$produk->id}", $this->dataValid([
            'nama' => 'Nasi Goreng Spesial',
            'harga' => 18000,
        ]))
            ->assertOk()
            ->assertJsonPath('data.nama', 'Nasi Goreng Spesial')
            ->assertJsonPath('data.harga', 18000);
    }

    public function test_bisa_menghapus_produk_beserta_foto(): void
    {
        Storage::fake('public');
        $foto = UploadedFile::fake()->image('nasi.png')->store('produk', 'public');
        $produk = Produk::create($this->dataValid(['foto' => $foto]));

        $this->deleteJson("/api/produk/{$produk->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('produk', ['id' => $produk->id]);
        Storage::disk('public')->assertMissing($foto);
    }
}
