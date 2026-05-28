<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SINKRONISASI BBM DI TANGKI</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #000; }
        .header { text-align: left; margin-bottom: 20px; }
        .title { font-size: 14pt; font-weight: bold; margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
        th { font-weight: bold; font-size: 10pt; }
        td { font-size: 10pt; }
        
        .bg-grey { background-color: #E2EFDA; }
        .bg-yellow { background-color: #FFF2CC; }
        .bg-green { background-color: #C6E0B4; }
        .bg-orange { background-color: #FCE4D6; }
    </style>
</head>
<body>
    @include('components.pdf-header')

    <div class="header">
        <h1 class="title">SINKRONISASI BBM DI TANGKI</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 15%;">Waktu Input</th>
                <th rowspan="2" style="width: 15%;">Petugas</th>
                <th colspan="2" class="bg-yellow">STOK BBM DI TANGKI</th>
                <th colspan="2" class="bg-orange">PEMAKAIAN</th>
                <th colspan="2" class="bg-green">SISA BBM DI TANGKI</th>
            </tr>
            <tr>
                <th class="bg-yellow" style="width: 11%;">PERTAMAX</th>
                <th class="bg-yellow" style="width: 12%;">PERTAMINA DEX</th>
                <th class="bg-orange" style="width: 11%;">PERTAMAX</th>
                <th class="bg-orange" style="width: 12%;">PERTAMINA DEX</th>
                <th class="bg-green" style="width: 11%;">PERTAMAX</th>
                <th class="bg-green" style="width: 13%;">PERTAMINA DEX</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allSyncs as $sync)
            <tr>
                <td>{{ $sync->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $sync->petugas->name ?? '-' }}</td>
                <td>{{ rtrim(rtrim(number_format($sync->stok_awal_pertamax, 2, '.', ''), '0'), '.') }}</td>
                <td>{{ rtrim(rtrim(number_format($sync->stok_awal_dex, 2, '.', ''), '0'), '.') }}</td>
                <td>{{ rtrim(rtrim(number_format($sync->pemakaian_pertamax, 2, '.', ''), '0'), '.') }}</td>
                <td>{{ rtrim(rtrim(number_format($sync->pemakaian_dex, 2, '.', ''), '0'), '.') }}</td>
                <td>{{ rtrim(rtrim(number_format($sync->sisa_pertamax, 2, '.', ''), '0'), '.') }}</td>
                <td>{{ rtrim(rtrim(number_format($sync->sisa_dex, 2, '.', ''), '0'), '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @include('components.pdf-signature')
</body>
</html>
