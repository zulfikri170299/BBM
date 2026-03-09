<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan BBM Tahunan {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; font-weight: bold; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    @include('components.pdf-header')

    <div class="header">
        LAPORAN BBM TAHUN {{ $year }}
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">NO</th>
                <th rowspan="2" style="width: 25%;">SATKER</th>
                <th colspan="2">PENDAPATAN</th>
                <th colspan="2">PEMAKAIAN</th>
                <th colspan="2">SISA PEMAKAIAN</th>
            </tr>
            <tr>
                <th>PERTAMAX</th>
                <th>PERTAMINA DEX</th>
                <th>PERTAMAX</th>
                <th>PERTAMINA DEX</th>
                <th>PERTAMAX</th>
                <th>PERTAMINA DEX</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $data['satker'] }}</td>
                <td>{{ number_format($data['pendapatan_pertamax'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['pendapatan_dex'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['pemakaian_pertamax'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['pemakaian_dex'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['sisa_pertamax'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['sisa_dex'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="font-bold">
                <td colspan="2">TOTAL</td>
                <td>{{ number_format($total['pendapatan_pertamax'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['pendapatan_dex'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['pemakaian_pertamax'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['pemakaian_dex'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['sisa_pertamax'], 0, ',', '.') }}</td>
                <td>{{ number_format($total['sisa_dex'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @include('components.pdf-signature')
</body>
</html>
