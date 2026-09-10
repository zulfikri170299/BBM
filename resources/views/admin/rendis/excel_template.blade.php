<table>
    <thead>
        <tr>
            <th colspan="16" style="font-weight: bold; text-align: center; font-size: 14px;">TEMPLATE IMPORT RENDIS BBM</th>
        </tr>
        <tr><th colspan="16"></th></tr>
        <tr>
            <th style="font-weight: bold;">Triwulan</th>
            <th>TW I</th>
            <th style="font-weight: bold;">Tahun</th>
            <th>{{ date('Y') }}</th>
            <th colspan="12">*(Isi TW I / TW II / TW III / TW IV)</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Pembelian Pertamax</th>
            <th>0</th>
            <th style="font-weight: bold;">Pembelian P. Dex</th>
            <th>0</th>
            <th style="font-weight: bold;">Susut (%)</th>
            <th>1.5</th>
            <th style="font-weight: bold; color: #10b981;">Netto Ptx:</th>
            <th style="font-weight: bold; color: #10b981;">=B4-(B4*(F4/100))</th>
            <th style="font-weight: bold; color: #10b981;">Netto Dex:</th>
            <th style="font-weight: bold; color: #10b981;">=D4-(D4*(F4/100))</th>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Hari Operasional (Bulan 1)</th>
            <th>Ops:</th><th>22</th>
            <th>Staff:</th><th>22</th>
            <th>Pimpinan:</th><th>30</th>
            <th colspan="9"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Hari Operasional (Bulan 2)</th>
            <th>Ops:</th><th>22</th>
            <th>Staff:</th><th>22</th>
            <th>Pimpinan:</th><th>30</th>
            <th colspan="9"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Hari Operasional (Bulan 3)</th>
            <th>Ops:</th><th>22</th>
            <th>Staff:</th><th>22</th>
            <th>Pimpinan:</th><th>30</th>
            <th colspan="9"></th>
        </tr>
        <tr><th colspan="16"></th></tr>
        <tr>
            <th rowspan="2" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">ID KENDARAAN (JANGAN DIUBAH)</th>
            <th rowspan="2" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">NO</th>
            <th rowspan="2" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">URAIAN</th>
            <th rowspan="2" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">JENIS RANDIS</th>
            <th rowspan="2" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">NOPOL</th>
            <th rowspan="2" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">JENIS BBM</th>
            
            <th colspan="4" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">BULAN 1</th>
            <th colspan="4" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">BULAN 2</th>
            <th colspan="4" style="font-weight: bold; background-color: #f3f4f6; text-align: center;">BULAN 3</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Indeks</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Hari</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Pertamax</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">P. Dex</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Indeks</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Hari</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Pertamax</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">P. Dex</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Indeks</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Hari</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">Pertamax</th>
            <th style="font-weight: bold; background-color: #f3f4f6; text-align: center;">P. Dex</th>
        </tr>
    </thead>
    <tbody>
        @php
            $currentSatker = null;
            $noSatker = 1;
            $roman = ['','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX','XXI','XXII','XXIII','XXIV','XXV'];
            $rowIdx = 10; // First data row starts at 11
            $satkerSubtotalRows = []; // To store row indices of subtotals for grand total
        @endphp
        
        @foreach($satkers as $satker)
            @if($satker->kendaraans->count() > 0)
                @php 
                    $rowIdx++; 
                    $startSatkerRow = $rowIdx + 1; // First vehicle of this satker
                @endphp
                <tr>
                    <td style="background-color: #1f2937;"></td>
                    <td style="font-weight: bold; text-align: center; color: #FFFF00; background-color: #1f2937;">{{ $roman[$noSatker] ?? $noSatker }}</td>
                    <td colspan="16" style="font-weight: bold; color: #FFFF00; background-color: #1f2937;">{{ $satker->nama_satker }}</td>
                </tr>
                @php $noK = 1; @endphp
                @foreach($satker->kendaraans as $k)
                    @php 
                        $rowIdx++;
                        $jenisBbm = strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')); 
                        $isPertamax = $jenisBbm === 'pertamax';
                        $isDex = $jenisBbm === 'pertamina_dex';
                        
                        // Dynamic Hari cell formulas based on Uraian (Column C)
                        $ifH1 = 'IF(C' . $rowIdx . '="Pimpinan", $G$5, IF(C' . $rowIdx . '="Staff", $E$5, $C$5))';
                        $ifH2 = 'IF(C' . $rowIdx . '="Pimpinan", $G$6, IF(C' . $rowIdx . '="Staff", $E$6, $C$6))';
                        $ifH3 = 'IF(C' . $rowIdx . '="Pimpinan", $G$7, IF(C' . $rowIdx . '="Staff", $E$7, $C$7))';
                        
                        $uraian = trim($k->kategori_kendaraan ?? '');
                        if(empty($uraian)) $uraian = 'Operasional';
                    @endphp
                    <tr>
                        <td>{{ $k->id }}</td>
                        <td style="text-align: center;">{{ $noK++ }}</td>
                        <td>{{ ucfirst(strtolower($uraian)) }}</td>
                        <td>{{ $k->jenis_kendaraan ?? '-' }}</td>
                        <td>{{ $k->no_polisi }}</td>
                        <td>{{ $k->jenis_bbm }}</td>
                        
                        {{-- B1 --}}
                        <td style="background-color: #e0f2fe;">0</td>
                        <td style="color: #6b7280;">="x " &amp; {{ $ifH1 }}</td>
                        <td>{{ $isPertamax ? '=G' . $rowIdx . '*' . $ifH1 : '0' }}</td>
                        <td>{{ $isDex ? '=G' . $rowIdx . '*' . $ifH1 : '0' }}</td>

                        {{-- B2 --}}
                        <td style="background-color: #e0f2fe;">0</td>
                        <td style="color: #6b7280;">="x " &amp; {{ $ifH2 }}</td>
                        <td>{{ $isPertamax ? '=K' . $rowIdx . '*' . $ifH2 : '0' }}</td>
                        <td>{{ $isDex ? '=K' . $rowIdx . '*' . $ifH2 : '0' }}</td>

                        {{-- B3 --}}
                        <td style="background-color: #e0f2fe;">0</td>
                        <td style="color: #6b7280;">="x " &amp; {{ $ifH3 }}</td>
                        <td>{{ $isPertamax ? '=O' . $rowIdx . '*' . $ifH3 : '0' }}</td>
                        <td>{{ $isDex ? '=O' . $rowIdx . '*' . $ifH3 : '0' }}</td>
                    </tr>
                @endforeach
                
                {{-- SUBTOTAL SATKER --}}
                @php 
                    $rowIdx++; 
                    $satkerSubtotalRows[] = $rowIdx;
                @endphp
                <tr>
                    <td colspan="6" style="font-weight: bold; text-align: right; color: #ffffff; background-color: #4b5563;">JUMLAH</td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="font-weight: bold; color: #60a5fa; background-color: #4b5563;">=SUM(I{{ $startSatkerRow }}:I{{ $rowIdx-1 }})</td>
                    <td style="font-weight: bold; color: #34d399; background-color: #4b5563;">=SUM(J{{ $startSatkerRow }}:J{{ $rowIdx-1 }})</td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="font-weight: bold; color: #60a5fa; background-color: #4b5563;">=SUM(M{{ $startSatkerRow }}:M{{ $rowIdx-1 }})</td>
                    <td style="font-weight: bold; color: #34d399; background-color: #4b5563;">=SUM(N{{ $startSatkerRow }}:N{{ $rowIdx-1 }})</td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="font-weight: bold; color: #60a5fa; background-color: #4b5563;">=SUM(Q{{ $startSatkerRow }}:Q{{ $rowIdx-1 }})</td>
                    <td style="font-weight: bold; color: #34d399; background-color: #4b5563;">=SUM(R{{ $startSatkerRow }}:R{{ $rowIdx-1 }})</td>
                </tr>
                
                @php $noSatker++; @endphp
            @endif
        @endforeach
        
        {{-- GRAND TOTAL --}}
        @php 
            $rowIdx++; 
            $subtotalsI = []; $subtotalsJ = []; $subtotalsM = []; $subtotalsN = []; $subtotalsQ = []; $subtotalsR = [];
            foreach($satkerSubtotalRows as $r) {
                $subtotalsI[] = 'I'.$r; $subtotalsJ[] = 'J'.$r;
                $subtotalsM[] = 'M'.$r; $subtotalsN[] = 'N'.$r;
                $subtotalsQ[] = 'Q'.$r; $subtotalsR[] = 'R'.$r;
            }
            $sumI = !empty($subtotalsI) ? '='.implode('+', $subtotalsI) : '0';
            $sumJ = !empty($subtotalsJ) ? '='.implode('+', $subtotalsJ) : '0';
            $sumM = !empty($subtotalsM) ? '='.implode('+', $subtotalsM) : '0';
            $sumN = !empty($subtotalsN) ? '='.implode('+', $subtotalsN) : '0';
            $sumQ = !empty($subtotalsQ) ? '='.implode('+', $subtotalsQ) : '0';
            $sumR = !empty($subtotalsR) ? '='.implode('+', $subtotalsR) : '0';
        @endphp
        <tr>
            <td colspan="6" style="font-weight: bold; text-align: right; color: #ffffff; background-color: #111827;">GRAND TOTAL</td>
            <td style="background-color: #111827;"></td>
            <td style="background-color: #111827;"></td>
            <td style="font-weight: bold; color: #60a5fa; background-color: #111827;">{{ $sumI }}</td>
            <td style="font-weight: bold; color: #34d399; background-color: #111827;">{{ $sumJ }}</td>
            <td style="background-color: #111827;"></td>
            <td style="background-color: #111827;"></td>
            <td style="font-weight: bold; color: #60a5fa; background-color: #111827;">{{ $sumM }}</td>
            <td style="font-weight: bold; color: #34d399; background-color: #111827;">{{ $sumN }}</td>
            <td style="background-color: #111827;"></td>
            <td style="background-color: #111827;"></td>
            <td style="font-weight: bold; color: #60a5fa; background-color: #111827;">{{ $sumQ }}</td>
            <td style="font-weight: bold; color: #34d399; background-color: #111827;">{{ $sumR }}</td>
        </tr>
    </tbody>
</table>
