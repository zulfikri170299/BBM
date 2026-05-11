{{-- PDF Signature Block Component for Slog --}}
{{-- Usage: @include('components.pdf-signature-slog', ['bulan' => $bulan, 'tahun' => $tahun]) --}}
@php
    $satkerId = null;
    if (auth()->check()) {
        $satkerId = auth()->user()->satker_id;
    }
    $penandaTangan = \App\Models\PenandaTangan::getForPdf($satkerId);
    $petugasSpbp = \App\Models\PetugasSpbp::orderBy('urutan')->get();
@endphp

@if($penandaTangan)
<table style="width: 100%; margin-top: 20px; border: none; border-collapse: collapse;">
    <tr>
        <td style="width: 60%; border: none; vertical-align: top; text-align: left;">
            <p style="margin: 0; font-size: 10pt; font-weight: bold;">PETUGAS SPBP POLDA NTB</p>
            <table style="border: none; margin-top: 5px; width: 100%;">
                @foreach($petugasSpbp as $index => $petugas)
                <tr>
                    <td style="border: none; padding: 2px 0 5px 0; width: 3%; vertical-align: top; text-align: right; padding-right: 5px;">
                        {{ $index + 1 }}.
                    </td>
                    <td style="border: none; padding: 2px 0 5px 0; width: 35%; vertical-align: top;">
                        <u>{{ $petugas->nama }}</u><br>
                        {{ $petugas->pangkat_nrp }}
                    </td>
                    <td style="border: none; padding: 2px 0 5px 0; width: 62%; vertical-align: top;">
                        : -----------------------------
                    </td>
                </tr>
                @endforeach
            </table>
        </td>
        <td style="width: 40%; border: none; text-align: center; vertical-align: top; white-space: nowrap;">
            <div style="display: inline-block; text-align: center;">
                <p style="margin: 0; font-size: 10pt;">Mataram, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</p>
                <p style="margin: 5px 0 0; font-size: 10pt;">{{ $penandaTangan->jabatan }}</p>
                @if($penandaTangan->jabatan2)
                    <p style="margin: 0; font-size: 10pt;">{{ $penandaTangan->jabatan2 }}</p>
                @endif
                
                <div style="margin-top: 50px;">
                    <table style="border-collapse: collapse; margin: 0 auto; width: auto; border: none;">
                        <tr>
                            <td style="border: none; border-bottom: 1px solid #000; text-align: center; padding: 0 5px; line-height: 1.3;">
                                <span style="font-size: 10pt;">{{ $penandaTangan->nama }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; text-align: center; padding: 1px 0 0; line-height: 1.3;">
                                <span style="font-size: 10pt;">{{ $penandaTangan->pangkat }} NRP {{ $penandaTangan->nrp }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>
@endif
