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

        .cards-grid {
            width: 100%;
        }

        .card-row {
            width: 100%;
            margin-bottom: 5mm;
            text-align: center;
        }

        .card {
            width: 50mm;
            height: 50mm;
            border: 2pt solid #e67e22;
            border-radius: 4mm;
            display: inline-block;
            vertical-align: top;
            margin: 0 4mm 0 0;
            overflow: hidden;
            position: relative;
            page-break-inside: avoid;
            background: #fff;
        }

        .card-top {
            background: #e67e22;
            color: #fff;
            text-align: center;
            padding: 2.5mm 2mm 2mm;
        }

        .usaha-name {
            font-size: 6pt;
            font-weight: 700;
            letter-spacing: 0.3pt;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-center {
            text-align: center;
            padding: 2mm 2mm 1mm;
        }

        .table-number {
            font-size: 30pt;
            font-weight: 800;
            color: #e67e22;
            line-height: 1;
        }

        .card-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 1.5mm 2mm;
            border-top: 0.5pt solid #f0e0cc;
            background: #fef9f3;
        }

        .table-date {
            font-size: 5.5pt;
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <div class="cards-grid">
        @foreach($mejas as $meja)
            @if($loop->index % 3 == 0 && $loop->index != 0)
                </div>
            @endif
            @if($loop->index % 3 == 0)
                <div class="card-row">
            @endif
            <div class="card">
                <div class="card-top">
                    <div class="usaha-name">{{ $profil->nama_usaha ?? 'POS' }}</div>
                </div>
                <div class="card-center">
                    <div class="table-number">{{ $meja->no_meja }}</div>
                </div>
                <div class="card-bottom">
                    <div class="table-date">{{ $meja->tanggal->format('d/m/Y') }}</div>
                </div>
            </div>
        @endforeach
        @if(count($mejas) % 3 != 0)
            </div>
        @endif
        @if(count($mejas) % 3 == 0 && count($mejas) > 0)
            </div>
        @endif
    </div>
</body>
</html>
