{{-- PDF Signature Block Component --}}
{{-- Usage: @include('components.pdf-signature') --}}
@php
    $satkerId = null;
    
    // Try to get satker_id from logged-in user
    if (auth()->check()) {
        $satkerId = auth()->user()->satker_id;
    }
    
    $penandaTangan = \App\Models\PenandaTangan::getForPdf($satkerId);
@endphp

@if($penandaTangan)
<table style="width: 100%; margin-top: 40px; border: none; border-collapse: collapse;">
    <tr>
        <td style="width: 80%; border: none;">&nbsp;</td>
        <td style="border: none; text-align: center; vertical-align: top; white-space: nowrap;">
            <div style="display: inline-block; text-align: center;">
                <p style="margin: 0; font-size: 10pt;">Mataram, {{ \Carbon\Carbon::now()->setTimezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
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
