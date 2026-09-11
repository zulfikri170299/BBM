<table>
    <thead>
        <tr>
            <th colspan="18" style="font-weight: bold; text-align: center; font-size: 14px;">TEMPLATE IMPORT RENDIS BBM</th>
        </tr>
        <tr><th colspan="18"></th></tr>
        <tr>
            <th style="font-weight: bold;">Triwulan</th>
            <th>TW I</th>
            <th style="font-weight: bold;">Tahun</th>
            <th>{{ date('Y') }}</th>
            <th colspan="14"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Pembelian Pertamax</th>
            <th>0</th>
            <th style="font-weight: bold;">Pembelian P. Dex</th>
            <th>0</th>
            <th style="font-weight: bold;">Susut (%)</th>
            <th>1.5</th>
            <th colspan="2"></th>
            <th style="font-weight: bold; text-align: center;">Uraian</th>
            <th style="font-weight: bold; text-align: center;">Pertamax</th>
            <th style="font-weight: bold; text-align: center;">Pertamina Dex</th>
            <th colspan="7"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">=IF($B$3="TW I","Januari",IF($B$3="TW II","April",IF($B$3="TW III","Juli",IF($B$3="TW IV","Oktober","Bulan 1"))))</th>
            <th>Ops:</th><th>22</th>
            <th>Staff:</th><th>22</th>
            <th>Pimpinan:</th><th>30</th>
            <th></th>
            <th style="font-weight: bold; color: #10b981; text-align: center;">Netto</th>
            <th style="font-weight: bold; color: #10b981;">=B4-(B4*(F4/100))</th>
            <th style="font-weight: bold; color: #10b981;">=D4-(D4*(F4/100))</th>
            <th colspan="7"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">=IF($B$3="TW I","Februari",IF($B$3="TW II","Mei",IF($B$3="TW III","Agustus",IF($B$3="TW IV","November","Bulan 2"))))</th>
            <th>Ops:</th><th>22</th>
            <th>Staff:</th><th>22</th>
            <th>Pimpinan:</th><th>30</th>
            <th></th>
            <th style="font-weight: bold; color: #f59e0b; text-align: center;">Total</th>
            <th style="font-weight: bold; color: #f59e0b;">=SUBTOTAL(9, I11:I2000)+SUBTOTAL(9, M11:M2000)+SUBTOTAL(9, Q11:Q2000)</th>
            <th style="font-weight: bold; color: #f59e0b;">=SUBTOTAL(9, J11:J2000)+SUBTOTAL(9, N11:N2000)+SUBTOTAL(9, R11:R2000)</th>
            <th colspan="7"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">=IF($B$3="TW I","Maret",IF($B$3="TW II","Juni",IF($B$3="TW III","September",IF($B$3="TW IV","Desember","Bulan 3"))))</th>
            <th>Ops:</th><th>22</th>
            <th>Staff:</th><th>22</th>
            <th>Pimpinan:</th><th>30</th>
            <th></th>
            <th style="font-weight: bold; color: #ef4444; text-align: center;">Sisa</th>
            <th style="font-weight: bold; color: #ef4444;">=J5-J6</th>
            <th style="font-weight: bold; color: #ef4444;">=K5-K6</th>
            <th colspan="7"></th>
        </tr>
        <tr><th colspan="18"></th></tr>
        <tr>
            <th rowspan="2" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">ID KENDARAAN</th>
            <th rowspan="2" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">NO</th>
            <th rowspan="2" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">URAIAN</th>
            <th rowspan="2" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">JENIS RANDIS</th>
            <th rowspan="2" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">NOPOL</th>
            <th rowspan="2" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">JENIS BBM</th>
            
            <th colspan="4" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">=UPPER(IF($B$3="TW I","Januari",IF($B$3="TW II","April",IF($B$3="TW III","Juli",IF($B$3="TW IV","Oktober","BULAN 1")))))</th>
            <th colspan="4" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">=UPPER(IF($B$3="TW I","Februari",IF($B$3="TW II","Mei",IF($B$3="TW III","Agustus",IF($B$3="TW IV","November","BULAN 2")))))</th>
            <th colspan="4" style="font-weight: bold; background-color: #1e3a8a; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #0f172a;">=UPPER(IF($B$3="TW I","Maret",IF($B$3="TW II","Juni",IF($B$3="TW III","September",IF($B$3="TW IV","Desember","BULAN 3")))))</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Indeks</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Hari</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Pertamax</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">P. Dex</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Indeks</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Hari</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Pertamax</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">P. Dex</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Indeks</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Hari</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">Pertamax</th>
            <th style="font-weight: bold; background-color: #2563eb; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #1e3a8a;">P. Dex</th>
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
                    <td style="font-weight: bold; color: #60a5fa; background-color: #4b5563;">=SUBTOTAL(9, I{{ $startSatkerRow }}:I{{ $rowIdx-1 }})</td>
                    <td style="font-weight: bold; color: #34d399; background-color: #4b5563;">=SUBTOTAL(9, J{{ $startSatkerRow }}:J{{ $rowIdx-1 }})</td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="font-weight: bold; color: #60a5fa; background-color: #4b5563;">=SUBTOTAL(9, M{{ $startSatkerRow }}:M{{ $rowIdx-1 }})</td>
                    <td style="font-weight: bold; color: #34d399; background-color: #4b5563;">=SUBTOTAL(9, N{{ $startSatkerRow }}:N{{ $rowIdx-1 }})</td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="background-color: #4b5563;"></td>
                    <td style="font-weight: bold; color: #60a5fa; background-color: #4b5563;">=SUBTOTAL(9, Q{{ $startSatkerRow }}:Q{{ $rowIdx-1 }})</td>
                    <td style="font-weight: bold; color: #34d399; background-color: #4b5563;">=SUBTOTAL(9, R{{ $startSatkerRow }}:R{{ $rowIdx-1 }})</td>
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
            $sumI = '=SUBTOTAL(9, I11:I'.($rowIdx-1).')';
            $sumJ = '=SUBTOTAL(9, J11:J'.($rowIdx-1).')';
            $sumM = '=SUBTOTAL(9, M11:M'.($rowIdx-1).')';
            $sumN = '=SUBTOTAL(9, N11:N'.($rowIdx-1).')';
            $sumQ = '=SUBTOTAL(9, Q11:Q'.($rowIdx-1).')';
            $sumR = '=SUBTOTAL(9, R11:R'.($rowIdx-1).')';
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
