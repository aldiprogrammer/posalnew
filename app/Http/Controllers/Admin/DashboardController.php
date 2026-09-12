<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Produk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const BULAN_INDO = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    private function exprJam(): string
    {
        return $this->isSqlite() ? "strftime('%H', tanggal)" : "DATE_FORMAT(tanggal, '%H')";
    }

    private function exprBulan(): string
    {
        return $this->isSqlite() ? "strftime('%Y-%m', tanggal)" : "DATE_FORMAT(tanggal, '%Y-%m')";
    }

    private function isSqlite(): bool
    {
        return DB::connection()->getDriverName() === 'sqlite';
    }

    public function __invoke()
    {
        $today = Carbon::today();

        $totalTransaksi = Order::count();
        $pendapatanHariIni = (int) Order::whereDate('tanggal', $today)->sum('total_harga');
        $totalProduk = Produk::count();
        $stokMenipis = Produk::whereNotNull('qty_all')->where('qty_all', '<=', 10)->count();

        $dataPerJam = Order::whereDate('tanggal', $today)
            ->selectRaw($this->exprJam().' as jam, SUM(total_harga) as total')
            ->groupBy('jam')
            ->pluck('total', 'jam');

        $penjualanPerJam = collect();
        for ($i = 0; $i <= 23; $i++) {
            $jam = sprintf('%02d', $i);
            $penjualanPerJam->put($jam.':00', (int) ($dataPerJam[$jam] ?? 0));
        }

        $awal = $today->copy()->subMonths(11)->startOfMonth();
        $dataBulan = Order::where('tanggal', '>=', $awal->format('Y-m-d'))
            ->selectRaw($this->exprBulan().' as bulan, SUM(total_harga) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $penjualanPerBulan = collect();
        for ($i = 11; $i >= 0; $i--) {
            $bulan = $today->copy()->subMonths($i);
            $key = $bulan->format('Y-m');
            $label = self::BULAN_INDO[(int) $bulan->format('n') - 1].' '.$bulan->format('y');
            $penjualanPerBulan->put($label, (int) ($dataBulan[$key] ?? 0));
        }

        $maxJam = $penjualanPerJam->max() ?: 1;
        $maxBulan = $penjualanPerBulan->max() ?: 1;

        return view('admin.dashboard', compact(
            'totalTransaksi',
            'pendapatanHariIni',
            'totalProduk',
            'stokMenipis',
            'penjualanPerJam',
            'penjualanPerBulan',
            'maxJam',
            'maxBulan',
        ));
    }
}
