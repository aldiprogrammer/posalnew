<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Order;
use App\Models\Pengguna;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPesananTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Pengguna::factory()->create());
    }

    private function dataValid(array $overrides = []): array
    {
        Order::create([
            'kode_order' => 'ORD-001',
            'total_harga' => 30000,
            'diskon' => 0,
            'meja' => 'A1',
            'tanggal' => '2026-08-22',
        ]);

        $kategori = Kategori::create(['nama' => 'Makanan']);
        $produk = Produk::create(['nama' => 'Nasi Goreng', 'kategori_id' => $kategori->id, 'harga' => 15000]);
        $kasir = Pengguna::create(['nama' => 'Kasir', 'username' => 'kasir', 'password' => 'rahasia123']);

        return array_merge([
            'kode_order' => 'ORD-001',
            'produk_id' => $produk->id,
            'harga' => 15000,
            'qty' => 2,
            'diskon' => 0,
            'tanggal' => '2026-08-22',
            'kasir_id' => (string) $kasir->id,
            'pembayaran' => 'tunai',
        ], $overrides);
    }

    public function test_halaman_order_item_menampilkan_tombol_tambah(): void
    {
        $this->get(route('admin.pesanan.index'))
            ->assertStatus(200)
            ->assertSee('Tambah Order Item')
            ->assertSee(route('admin.pesanan.store'));
    }

    public function test_bisa_menambahkan_order_item_dari_halaman_admin(): void
    {
        $data = $this->dataValid(['pembayaran' => 'kartu debit']);

        $this->post(route('admin.pesanan.store'), $data)
            ->assertRedirect(route('admin.pesanan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('order_items', [
            'kode_order' => 'ORD-001',
            'qty' => 2,
            'pembayaran' => 'kartu debit',
        ]);
    }

    public function test_diskon_kosong_otomatis_nol_saat_menambahkan(): void
    {
        $data = $this->dataValid();
        unset($data['diskon']);

        $this->post(route('admin.pesanan.store'), $data)
            ->assertRedirect(route('admin.pesanan.index'));

        $this->assertDatabaseHas('order_items', [
            'kode_order' => 'ORD-001',
            'diskon' => 0,
        ]);
    }

    public function test_bisa_menambahkan_dengan_kode_order_bebas(): void
    {
        $data = $this->dataValid(['kode_order' => 'ORD-BEBAS-999']);

        $this->post(route('admin.pesanan.store'), $data)
            ->assertRedirect(route('admin.pesanan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('order_items', ['kode_order' => 'ORD-BEBAS-999']);
    }

    public function test_validasi_wajib_diisi(): void
    {
        $this->post(route('admin.pesanan.store'), [])
            ->assertRedirect()
            ->assertSessionHasErrors(['kode_order', 'produk_id', 'harga', 'qty', 'tanggal']);
    }
}
