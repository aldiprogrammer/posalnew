<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Profil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanOrderController extends Controller
{
    public function index(Request $request): View
    {
        $periode = $request->get('periode', 'hari_ini');
        $bulan = $request->get('bulan');

        $base = $this->baseQuery($periode, $bulan);

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

    public function exportPdf(Request $request)
    {
        $periode = $request->get('periode', 'hari_ini');
        $bulan = $request->get('bulan');

        $base = $this->baseQuery($periode, $bulan);

        $statistik = (clone $base)->selectRaw(
            'COUNT(*) as jumlah_order, COALESCE(SUM(total_harga), 0) as total_penjualan, COALESCE(SUM(diskon), 0) as total_diskon'
        )->first();

        $orders = (clone $base)->with('member')
            ->withSum('items as jumlah_item', 'qty')
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $profil = Profil::first();

        $pdf = Pdf::loadView('admin.laporan-order.pdf', compact('orders', 'statistik', 'profil', 'periode', 'bulan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-order.pdf');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $periode = $request->get('periode', 'hari_ini');
        $bulan = $request->get('bulan');

        $base = $this->baseQuery($periode, $bulan);

        $orders = (clone $base)->with('member')
            ->withSum('items as jumlah_item', 'qty')
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Order');

        $headers = ['No', 'Kode Order', 'Tanggal', 'Jumlah Item', 'Total Harga', 'Diskon', 'Member', 'No Meja', 'Status Cetak'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        $row = 2;
        foreach ($orders as $index => $order) {
            $sheet->setCellValue('A'.$row, $index + 1);
            $sheet->setCellValue('B'.$row, $order->kode_order);
            $sheet->setCellValue('C'.$row, $order->tanggal->format('d/m/Y'));
            $sheet->setCellValue('D'.$row, $order->jumlah_item);
            $sheet->setCellValue('E'.$row, $order->total_harga);
            $sheet->setCellValue('F'.$row, $order->diskon);
            $sheet->setCellValue('G'.$row, $order->member->nama ?? 'Non Member');
            $sheet->setCellValue('H'.$row, $order->meja);
            $sheet->setCellValue('I'.$row, $order->status_cetak ? 'Dicetak' : 'Belum Dicetak');
            $row++;
        }

        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'] as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="laporan-order.xlsx"',
        ]);
    }

    private function baseQuery(string &$periode, ?string &$bulan): Builder
    {
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
                    $bulan = null;
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

        return $base;
    }
}
