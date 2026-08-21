<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Pengguna::factory()->create());
    }

    private function buatOrder(string $kode, string $tanggal, int $total): void
    {
        Order::create([
            'kode_order' => $kode,
            'total_harga' => $total,
            'diskon' => 0,
            'member_id' => null,
            'meja' => 'A1',
            'tanggal' => $tanggal,
            'status_cetak' => false,
        ]);
    }

    public function test_laporan_filter_periode_hari_ini_bulan_dan_semua(): void
    {
        $hariIni = today();
        $awalBulan = now()->startOfMonth()->toDateString();
        $bulanLalu = now()->subMonthsNoOverflow()->setDay(15)->toDateString();

        $this->buatOrder('ORD-HARI-INI', $hariIni->toDateString(), 50000);
        $this->buatOrder('ORD-BULAN-LALU', $bulanLalu, 20000);

        $adaAwalBulan = ! $hariIni->isSameDay(now()->startOfMonth());
        if ($adaAwalBulan) {
            $this->buatOrder('ORD-AWAL-BULAN', $awalBulan, 30000);
        }

        // Default tanpa parameter = hari ini
        $this->get(route('admin.laporan-order.index'))
            ->assertOk()
            ->assertSee('ORD-HARI-INI')
            ->assertDontSee('ORD-BULAN-LALU');

        // Filter hari ini
        $this->get(route('admin.laporan-order.index', ['periode' => 'hari_ini']))
            ->assertOk()
            ->assertSee('ORD-HARI-INI')
            ->assertDontSee('ORD-BULAN-LALU');

        // Filter bulan ini
        $responseBulanIni = $this->get(route('admin.laporan-order.index', ['periode' => 'bulan_ini']))
            ->assertOk()
            ->assertSee('ORD-HARI-INI')
            ->assertDontSee('ORD-BULAN-LALU');

        if ($adaAwalBulan) {
            $responseBulanIni->assertSee('ORD-AWAL-BULAN');
        }

        // Filter bulan tertentu (bulan lalu)
        $bulanLaluFormat = now()->subMonthsNoOverflow()->format('Y-m');
        $this->get(route('admin.laporan-order.index', ['periode' => 'bulan', 'bulan' => $bulanLaluFormat]))
            ->assertOk()
            ->assertSee('ORD-BULAN-LALU')
            ->assertDontSee('ORD-HARI-INI');

        // Semua periode + ringkasan
        $totalSemua = 50000 + 20000 + ($adaAwalBulan ? 30000 : 0);
        $this->get(route('admin.laporan-order.index', ['periode' => 'semua']))
            ->assertOk()
            ->assertSee('ORD-HARI-INI')
            ->assertSee('ORD-BULAN-LALU')
            ->assertSee('Rp '.number_format($totalSemua, 0, ',', '.'));
    }
}
