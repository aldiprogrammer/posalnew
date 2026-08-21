<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Order;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemApiTest extends TestCase
{
    use RefreshDatabase;

    private function buatOrder(): Order
    {
        return Order::firstOrCreate(
            ['kode_order' => 'ORD-001'],
            [
                'total_harga' => 30000,
                'diskon' => 0,
                'meja' => 'A1',
                'tanggal' => '2026-08-21',
            ]
        );
    }

    private function dataValid(array $overrides = []): array
    {
        $this->buatOrder();
        $kategori = Kategori::create(['nama' => 'Makanan']);
        $produk = Produk::create(['nama' => 'Nasi Goreng', 'kategori_id' => $kategori->id, 'harga' => 15000]);
        $kasir = Pengguna::firstOrCreate(
            ['username' => 'kasir'],
            ['nama' => 'Kasir', 'password' => 'rahasia123']
        );

        return array_merge([
            'kode_order' => 'ORD-001',
            'produk_id' => $produk->id,
            'harga' => 15000,
            'qty' => 2,
            'diskon' => 0,
            'tanggal' => '2026-08-21',
            'kasir_id' => (string) $kasir->id,
            'pembayaran' => 'transfer',
        ], $overrides);
    }

    public function test_bisa_mengambil_daftar_order_items(): void
    {
        Pesanan::create($this->dataValid());

        $this->getJson('/api/order-items')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.produk.nama', 'Nasi Goreng')
            ->assertJsonPath('data.0.kasir.nama', 'Kasir');
    }

    public function test_bisa_menambahkan_order_item(): void
    {
        $this->postJson('/api/order-items', $this->dataValid())
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.qty', 2)
            ->assertJsonPath('data.pembayaran', 'transfer');

        $this->assertDatabaseHas('order_items', ['kode_order' => 'ORD-001']);
    }

    public function test_kode_order_harus_ada_di_tabel_order(): void
    {
        $this->postJson('/api/order-items', $this->dataValid(['kode_order' => 'ORD-TIDAK-ADA']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['kode_order']);
    }

    public function test_bisa_menambahkan_order_item_tanpa_kasir(): void
    {
        $data = $this->dataValid();
        unset($data['kasir_id']);

        $this->postJson('/api/order-items', $data)
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('order_items', [
            'kode_order' => 'ORD-001',
            'kasir_id' => null,
        ]);
    }

    public function test_bisa_mengubah_order_item(): void
    {
        $item = Pesanan::create($this->dataValid());

        $this->putJson("/api/order-items/{$item->id}", $this->dataValid([
            'qty' => 3,
            'pembayaran' => 'qris',
        ]))
            ->assertOk()
            ->assertJsonPath('data.qty', 3)
            ->assertJsonPath('data.pembayaran', 'qris');
    }

    public function test_bisa_menghapus_order_item(): void
    {
        $item = Pesanan::create($this->dataValid());

        $this->deleteJson("/api/order-items/{$item->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
    }
}
