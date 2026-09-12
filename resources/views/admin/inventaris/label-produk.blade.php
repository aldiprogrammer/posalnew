<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 8mm;
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
        }

        .labels {
            width: 100%;
        }

        .label {
            width: 88mm;
            display: inline-block;
            vertical-align: top;
            margin: 0 2mm 4mm 0;
            border: 1.5pt solid #e67e22;
            border-radius: 3mm;
            overflow: hidden;
            page-break-inside: avoid;
            background: #fff;
        }

        .label-top {
            background: #e67e22;
            color: #fff;
            text-align: center;
            padding: 2.5mm 3mm 2mm;
        }

        .label-top .usaha-name {
            font-size: 7pt;
            font-weight: 700;
            letter-spacing: 0.3pt;
            text-transform: uppercase;
        }

        .label-body {
            padding: 3mm 4mm 2.5mm;
            text-align: center;
        }

        .label-kode {
            font-size: 15pt;
            font-weight: 800;
            color: #e67e22;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5pt;
            margin-bottom: 1.5mm;
        }

        .label-nama {
            font-size: 9pt;
            font-weight: 600;
            line-height: 1.3;
            min-height: 10mm;
        }

        .label-bottom {
            border-top: 0.5pt solid #f0e0cc;
            background: #fef9f3;
            text-align: center;
            padding: 1.5mm 3mm;
        }

        .label-bottom .label-tgl {
            font-size: 6.5pt;
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <div class="labels">
        @foreach($inventaris as $item)
            <div class="label">
                <div class="label-top">
                    <div class="usaha-name">{{ $profil->nama_usaha ?? 'POS' }}</div>
                </div>
                <div class="label-body">
                    <div class="label-kode">{{ $item->kode_barang }}</div>
                    <div class="label-nama">{{ $item->nama_barang }}</div>
                </div>
                <div class="label-bottom">
                    <div class="label-tgl">Tgl Masuk: {{ $item->tgl_masuk->format('d/m/Y') }}</div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>