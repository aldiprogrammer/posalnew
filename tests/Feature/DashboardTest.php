<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Order;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['jenis_usaha' => 'Toko']);
        $this->actingAs($this->user);
    }

    private function buatOrder(string $tanggal, int $total): Order
    {
        return Order::create([
            'kode_order' => 'ORD-'.Str::upper(Str::random(6)),
            'total_harga' => $total,
            'diskon' => 0,
            'meja' => 'A1',
            'tanggal' => $tanggal,
        ]);
    }

    private function buatProduk(int $qtyAll): Produk
    {
        $kategori = Kategori::create(['nama' => 'Kategori '.Str::random(4)]);

        return Produk::create([
            'nama' => 'Produk '.Str::random(4),
            'kategori_id' => $kategori->id,
            'harga' => 5000,
            'qty_all' => $qtyAll,
        ]);
    }

    public function test_dashboard_menampilkan_statistik_dan_grafik(): void
    {
        $this->buatOrder(now()->format('Y-m-d H:i:s'), 50000);
        $this->buatOrder(now()->format('Y-m-d H:i:s'), 25000);
        $this->buatProduk(5);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Rp 75.000')
            ->assertSee('Grafik Penjualan Hari Ini')
            ->assertSee('Grafik Penjualan Per Bulan');
    }

    public function test_dashboard_tidak_gagal_saat_belum_ada_transaksi(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Grafik Penjualan Hari Ini')
            ->assertSee('Grafik Penjualan Per Bulan');
    }

    public function test_pendapatan_grafik_hari_ini_hanya_untuk_tanggal_sekarang(): void
    {
        $this->buatOrder(now()->format('Y-m-d').' 09:30:00', 40000);
        $this->buatOrder(now()->subDay()->format('Y-m-d').' 09:30:00', 90000);

        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();
        $this->assertStringContainsString('Rp 40.000', $response->getContent());
        $this->assertStringNotContainsString('Rp 90.000', $response->getContent());
    }
}
