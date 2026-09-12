<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdukFormTest extends TestCase
{
    use RefreshDatabase;

    private function login(string $jenisUsaha): void
    {
        $this->actingAs(User::factory()->create(['jenis_usaha' => $jenisUsaha]));
    }

    private function kategori(): Kategori
    {
        return Kategori::create(['nama' => 'Kategori Test']);
    }

    public function test_usaha_makanan_hanya_memakai_harga_dan_mengabaikan_satuan(): void
    {
        $this->login('Restoran');

        $this->post(route('admin.produk.store'), [
            'kategori_id' => $this->kategori()->id,
            'nama' => 'Es Teh',
            'harga' => 5000,
            'diskon' => 0,
            'stok' => 'tersedia',
            'satuan_besar' => 'Dus',
            'isi' => 12,
            'qty' => 2,
            'harga_satuan_besar' => 60000,
            'satuan_kecil' => 'Botol',
            'qty_all' => 24,
            'harga_satuan_kecil' => 5000,
        ])->assertRedirect(route('admin.produk.index'));

        $produk = Produk::first();
        $this->assertSame(5000, (int) $produk->harga);
        $this->assertNull($produk->satuan_besar);
        $this->assertNull($produk->isi);
        $this->assertNull($produk->qty_all);
    }

    public function test_usaha_makanan_wajib_mengisi_harga(): void
    {
        $this->login('Cafe');

        $this->post(route('admin.produk.store'), [
            'kategori_id' => $this->kategori()->id,
            'nama' => 'Kopi',
            'harga' => null,
            'stok' => 'tersedia',
        ])->assertSessionHasErrors('harga');

        $this->assertDatabaseCount('produk', 0);
    }

    public function test_usaha_toko_tidak_memakai_harga_dan_menyimpan_satuan(): void
    {
        $this->login('Toko');

        $this->post(route('admin.produk.store'), [
            'kategori_id' => $this->kategori()->id,
            'nama' => 'Beras 5kg',
            'stok' => 'tersedia',
            'satuan_besar' => 'Kotak',
            'isi' => 10,
            'qty' => 3,
            'harga_satuan_besar' => 70000,
            'satuan_kecil' => 'Pcs',
            'qty_all' => 30,
            'harga_satuan_kecil' => 7000,
        ])->assertRedirect(route('admin.produk.index'));

        $produk = Produk::first();
        $this->assertSame('Kotak', $produk->satuan_besar);
        $this->assertSame(10, $produk->isi);
        $this->assertSame(30, $produk->qty_all);
        $this->assertSame(7000, $produk->harga_satuan_kecil);
        $this->assertSame(0, (int) $produk->harga);
    }

    public function test_halaman_produk_menampilkan_satuan_untuk_toko(): void
    {
        $this->login('Toko');
        Produk::create([
            'kategori_id' => $this->kategori()->id,
            'nama' => 'Minyak 1L',
            'stok' => 'tersedia',
            'satuan_besar' => 'Dus',
            'isi' => 12,
            'qty' => 2,
            'qty_all' => 24,
            'satuan_kecil' => 'Botol',
            'harga_satuan_kecil' => 15000,
        ]);

        $this->get(route('admin.produk.index'))
            ->assertOk()
            ->assertSeeText('Satuan & Qty All')
            ->assertSeeText('Harga Satuan Kecil');
    }

    public function test_halaman_produk_menampilkan_harga_untuk_makanan(): void
    {
        $this->login('Restoran');
        Produk::create([
            'kategori_id' => $this->kategori()->id,
            'nama' => 'Nasi Goreng',
            'harga' => 15000,
            'diskon' => 1000,
            'stok' => 'tersedia',
        ]);

        $this->get(route('admin.produk.index'))
            ->assertOk()
            ->assertSeeText('Diskon')
            ->assertDontSeeText('Satuan & Qty All');
    }
}
