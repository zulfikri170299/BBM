<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian BBM</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        th { background-color: #f9f9f9; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .text-left { text-align: left; }
        .bg-gray { background-color: #f5f5f5; }
        .font-bold { font-weight: bold; }
        .text-indigo { color: #4f46e5; }
        .footer { margin-top: 30px; text-align: right; }
        .summary-table th { background-color: #333; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HARIAN BBM</h1>
        <p>Periode: {{ Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th width="65">TANGGAL</th>
                <th>JENIS BBM</th>
                <th>METER AWAL</th>
                <th>METER AKHIR</th>
                <th>OUTPUT FISIK</th>
                <th>LOG APLIKASI</th>
                <th>SELISIH</th>
                <th width="80">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $i = 1;
                $totalPertamaxManual = 0;
                $totalPertamaxApp = 0;
                $totalDexManual = 0;
                $totalDexApp = 0;
            @endphp
            @foreach($dates as $date)
                @php
                    $carbonDate = Carbon\Carbon::parse($date);
                    $types = ['PERTAMAX', 'PERTAMINA DEX'];
                @endphp
                @foreach($types as $type)
                    @php
                        $manual = $manualData->get($date)?->where('jenis_bbm', $type)->first();
                        $appTotal = $appData->get($date)?->where('bbm_alias', $type)->first()?->total ?? 0;
                        $manualTotal = $manual ? ($manual->meter_akhir - $manual->meter_awal) : 0;
                        $diff = $appTotal - $manualTotal;
                        
                        if($type == 'PERTAMAX') {
                            $totalPertamaxManual += $manualTotal;
                            $totalPertamaxApp += $appTotal;
                        } else {
                            $totalDexManual += $manualTotal;
                            $totalDexApp += $appTotal;
                        }
                    @endphp
                    <tr>
                        @if($loop->first)
                        <td rowspan="2">{{ $i++ }}</td>
                        <td rowspan="2">{{ $carbonDate->translatedFormat('d/m/Y') }}</td>
                        @endif
                        <td class="text-left font-bold">{{ $type }}</td>
                        <td>{{ $manual !== null && $manual->meter_awal != 0 ? number_format($manual->meter_awal, 0, ',', '.') : '' }}</td>
                        <td>{{ $manual !== null && $manual->meter_akhir != 0 ? number_format($manual->meter_akhir, 0, ',', '.') : '' }}</td>
                        <td class="bg-gray font-bold">{{ $manual !== null ? number_format($manualTotal, 0, ',', '.') : '' }}</td>
                        @php $hasAppData = $appData->get($date)?->where('bbm_alias', $type)->isNotEmpty(); @endphp
                        <td>{{ $hasAppData ? number_format($appTotal, 0, ',', '.') : '' }}</td>
                        <td class="font-bold {{ $diff != 0 ? 'color: red' : '' }}">{{ ($manual !== null || $hasAppData) ? number_format($diff, 0, ',', '.') : '' }}</td>
                        <td class="text-left" style="font-size: 8px;">{{ $manual ? $manual->keterangan : '' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
        <thead>
            <tr>
                <th colspan="2">RINGKASAN TOTAL BULANAN</th>
                <th>TOTAL FISIK</th>
                <th>TOTAL APLIKASI</th>
                <th>TOTAL SELISIH</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" class="text-left font-bold">PERTAMAX</td>
                <td class="font-bold">{{ number_format($totalPertamaxManual, 0, ',', '.') }}</td>
                <td class="font-bold">{{ number_format($totalPertamaxApp, 0, ',', '.') }}</td>
                <td class="font-bold">{{ number_format($totalPertamaxApp - $totalPertamaxManual, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-left font-bold">PERTAMINA DEX</td>
                <td class="font-bold">{{ number_format($totalDexManual, 0, ',', '.') }}</td>
                <td class="font-bold">{{ number_format($totalDexApp, 0, ',', '.') }}</td>
                <td class="font-bold">{{ number_format($totalDexApp - $totalDexManual, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
        <p>Oleh: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>
