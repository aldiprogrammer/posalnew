<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LaporanOrderController extends Controller
{
    public function index(Request $request): View
    {
        $periode = $request->get('periode', 'hari_ini');
        $bulan = $request->get('bulan');

        $base = Order::query();

        switch ($periode) {
            case 'semua':
                break;
            case 'bulan':
                if ($bulan && preg_match('/^\d{4}-\d{2}$/', $bulan)) {
                    [$tahun, $nomorBulan] = explode('-', $bulan);
                    $base->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $nomorBulan);
                } else {
                    $periode = 'bulan_ini';
                    $base->whereMonth('tanggal', now()->month)
                        ->whereYear('tanggal', now()->year);
                }
                break;
            case 'bulan_ini':
                $base->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
                break;
            case 'hari_ini':
            default:
                $periode = 'hari_ini';
                $base->whereDate('tanggal', today());
                break;
        }

        $statistik = (clone $base)->selectRaw(
            'COUNT(*) as jumlah_order, COALESCE(SUM(total_harga), 0) as total_penjualan, COALESCE(SUM(diskon), 0) as total_diskon'
        )->first();

        $orders = (clone $base)->with('member')
            ->withSum('items as jumlah_item', 'qty')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.laporan-order.index', compact('orders', 'statistik', 'periode', 'bulan'));
    }
}
