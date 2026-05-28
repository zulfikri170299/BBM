<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 10mm;
            size: a4 portrait;
            /* F4 Portrait */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 14pt;
            margin: 0;
            font-weight: bold;
        }

        .header h2 {
            font-size: 14pt;
            margin: 5px 0 0 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
        }

        .text-left {
            text-align: left;
            padding-left: 10px;
        }

        .bold {
            font-weight: bold;
        }

        .col-no {
            width: 20pt;
            padding: 5px 2px;
        }

        .col-satker {
            white-space: nowrap;
        }

        .col-bbm {
            width: 80pt;
            white-space: nowrap;
        }

        tfoot td {
            font-weight: bold;
            font-size: 12pt;
        }
    </style>
</head>

<body>
    @include('components.pdf-header')
    <div class="header">
        <h1>{{ $title }}</h1>
        <h2>{{ $periode }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="col-no">NO</th>
                <th rowspan="2" class="col-satker">SATKER</th>
                <th colspan="2">SISA BBM</th>
            </tr>
            <tr>
                <th class="col-bbm">PERTAMAX</th>
                <th class="col-bbm">PERTAMINA DEX</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ strtoupper($row['satker']) }}</td>
                    <td>{{ number_format($row['pertamax'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['dex'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="bold">JUMLAH</td>
                <td class="bold">{{ number_format($totalPertamax, 0, ',', '.') }}</td>
                <td class="bold">{{ number_format($totalDex, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    @include('components.pdf-signature')
</body>

</html>