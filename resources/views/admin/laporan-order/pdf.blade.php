<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1a1a2e;
            font-size: 9pt;
        }

        @php
            $namaBulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            if ($periode === 'hari_ini') {
                $labelPeriode = 'Hari Ini — ' . today()->format('d/m/Y');
            } elseif ($periode === 'bulan_ini') {
                $labelPeriode = 'Bulan Ini — ' . $namaBulan[now()->month] . ' ' . now()->year;
            } elseif ($periode === 'bulan' && $bulan) {
                [$tahun, $nomorBulan] = explode('-', $bulan);
                $labelPeriode = 'Bulan ' . $namaBulan[(int) $nomorBulan] . ' ' . $tahun;
            } else {
                $labelPeriode = 'Semua Periode';
            }

            $rataRata = $statistik->jumlah_order > 0
                ? (int) round($statistik->total_penjualan / $statistik->jumlah_order)
                : 0;
        @endphp

        .header {
            text-align: center;
            border-bottom: 2pt solid #e67e22;
            padding-bottom: 4mm;
            margin-bottom: 5mm;
        }

        .header .usaha {
            font-size: 15pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        .header .alamat {
            font-size: 7.5pt;
            color: #555;
            margin-top: 1mm;
        }

        .header .title {
            font-size: 11pt;
            font-weight: 700;
            margin-top: 2.5mm;
        }

        .header .periode {
            font-size: 8pt;
            color: #666;
            margin-top: 1mm;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
        }

        .summary-table td {
            border: 0.5pt solid #ddd;
            padding: 3mm 4mm;
        }

        .summary-label {
            font-size: 7pt;
            color: #888;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 11pt;
            font-weight: 800;
            margin-top: 1mm;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #fdf1e6;
            color: #b45309;
            border: 0.5pt solid #e0c9a8;
            padding: 2.5mm 2mm;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }

        .data-table td {
            border: 0.5pt solid #ddd;
            padding: 2.2mm 2mm;
            font-size: 7.5pt;
        }

        .data-table .right {
            text-align: right;
        }

        .data-table .center {
            text-align: center;
        }

        .footer {
            margin-top: 6mm;
            text-align: right;
            font-size: 7.5pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="usaha">{{ $profil->nama_usaha ?? 'POS' }}</div>
        @if (!empty($profil->alamat))
            <div class="alamat">{{ $profil->alamat }}</div>
        @endif
        <div class="title">Laporan Order</div>
        <div class="periode">Periode: {{ $labelPeriode }}</div>
    </div>

    <table class="summary-table">
        <tr>
            <td style="width: 25%">
                <div class="summary-label">Jumlah Order</div>
                <div class="summary-value">{{ number_format($statistik->jumlah_order, 0, ',', '.') }}</div>
            </td>
            <td style="width: 25%">
                <div class="summary-label">Total Penjualan</div>
                <div class="summary-value">Rp {{ number_format($statistik->total_penjualan, 0, ',', '.') }}</div>
            </td>
            <td style="width: 25%">
                <div class="summary-label">Total Diskon</div>
                <div class="summary-value">Rp {{ number_format($statistik->total_diskon, 0, ',', '.') }}</div>
            </td>
            <td style="width: 25%">
                <div class="summary-label">Rata-rata / Order</div>
                <div class="summary-value">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:6%">No</th>
                <th>Kode Order</th>
                <th>Tanggal</th>
                <th class="center">Jumlah Item</th>
                <th class="right">Total Harga</th>
                <th class="right">Diskon</th>
                <th>Member</th>
                <th class="center">No Meja</th>
                <th class="center">Status Cetak</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->kode_order }}</td>
                    <td>{{ $order->tanggal->format('d/m/Y') }}</td>
                    <td class="center">{{ $order->jumlah_item }}</td>
                    <td class="right">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td class="right">{{ $order->diskon > 0 ? 'Rp '.number_format($order->diskon, 0, ',', '.') : '-' }}</td>
                    <td>{{ $order->member->nama ?? 'Non Member' }}</td>
                    <td class="center">{{ $order->meja }}</td>
                    <td class="center">{{ $order->status_cetak ? 'Dicetak' : 'Belum Dicetak' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:6mm; color:#888;">
                        Tidak ada order pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>