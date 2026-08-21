<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Order;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPageSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Pengguna::factory()->create());
    }

    public function test_order_index_shows_orders_and_detail_items(): void
    {
        $kategori = Kategori::create(['nama' => 'Makanan']);
        $produk = Produk::create(['nama' => 'Nasi Goreng', 'kategori_id' => $kategori->id, 'harga' => 15000]);
        $kasir = Pegawai::create(['nama' => 'Kasir Satu', 'jabatan' => 'Kasir', 'jenis_kelamin' => 'Laki-laki']);
        $member = Member::create(['nama' => 'Budi', 'nik' => '3201234567890001', 'tanggal_bergabung' => '2026-01-01']);

        Order::create([
            'kode_order' => 'ORD-001',
            'total_harga' => 30000,
            'diskon' => 5000,
            'member_id' => $member->id,
            'meja' => 'A1',
            'tanggal' => '2026-08-21',
            'status_cetak' => false,
        ]);

        Pesanan::create([
            'kode_order' => 'ORD-001',
            'produk_id' => $produk->id,
            'harga' => 15000,
            'qty' => 2,
            'diskon' => 0,
            'tanggal' => '2026-08-21',
            'kasir_id' => $kasir->id,
        ]);

        $response = $this->get(route('admin.order.index'));

        $response->assertStatus(200);
        $response->assertSee('ORD-001');
        $response->assertSee('Nasi Goreng');
        $response->assertSee('Budi');
        $response->assertSee('Belum Dicetak');
    }
}
