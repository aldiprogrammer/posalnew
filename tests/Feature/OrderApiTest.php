<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Order;
use App\Models\Pegawai;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private function buatOrder(array $overrides = []): array
    {
        $member = Member::firstOrCreate(
            ['nik' => '3201234567890001'],
            ['nama' => 'Budi', 'tanggal_bergabung' => '2026-01-01']
        );

        return array_merge([
            'kode_order' => 'ORD-001',
            'total_harga' => 30000,
            'diskon' => 5000,
            'member_id' => $member->id,
            'meja' => 'A1',
            'pembayaran' => 'tunai',
            'tanggal' => '2026-08-21',
        ], $overrides);
    }

    public function test_bisa_mengambil_daftar_order_dengan_item(): void
    {
        $kategori = Kategori::create(['nama' => 'Makanan']);
        $produk = Produk::create(['nama' => 'Nasi Goreng', 'kategori_id' => $kategori->id, 'harga' => 15000]);
        $kasir = Pegawai::create(['nama' => 'Kasir', 'jabatan' => 'Kasir', 'jenis_kelamin' => 'Laki-laki']);

        Order::create($this->buatOrder());
        Pesanan::create([
            'kode_order' => 'ORD-001',
            'produk_id' => $produk->id,
            'harga' => 15000,
            'qty' => 2,
            'diskon' => 0,
            'tanggal' => '2026-08-21',
            'kasir_id' => $kasir->id,
            'pembayaran' => 'tunai',
        ]);

        $this->getJson('/api/order')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.kode_order', 'ORD-001')
            ->assertJsonPath('data.0.member.nama', 'Budi')
            ->assertJsonPath('data.0.jumlah_item', 2)
            ->assertJsonPath('data.0.items.0.produk.nama', 'Nasi Goreng');
    }

    public function test_bisa_menambahkan_order(): void
    {
        $this->postJson('/api/order', $this->buatOrder())
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.kode_order', 'ORD-001')
            ->assertJsonPath('data.pembayaran', 'tunai');

        $this->assertDatabaseHas('order', ['kode_order' => 'ORD-001']);
    }

    public function test_validasi_kode_order_wajib_dan_unik(): void
    {
        Order::create($this->buatOrder());

        $this->postJson('/api/order', $this->buatOrder(['meja' => 'A2']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['kode_order']);

        $this->postJson('/api/order', $this->buatOrder(['kode_order' => '', 'pembayaran' => 'kartu']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['kode_order', 'pembayaran']);
    }

    public function test_bisa_mengubah_order(): void
    {
        $order = Order::create($this->buatOrder());

        $this->putJson("/api/order/{$order->id}", $this->buatOrder([
            'total_harga' => 45000,
            'pembayaran' => 'qris',
        ]))
            ->assertOk()
            ->assertJsonPath('data.total_harga', 45000)
            ->assertJsonPath('data.pembayaran', 'qris');
    }

    public function test_bisa_menghapus_order(): void
    {
        $order = Order::create($this->buatOrder());

        $this->deleteJson("/api/order/{$order->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('order', ['id' => $order->id]);
    }
}
