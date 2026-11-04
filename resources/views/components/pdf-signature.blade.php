{{-- PDF Signature Block Component --}}
{{-- Usage: @include('components.pdf-signature', ['satkerId' => $satkerId]) --}}
@php
    // 1. Cek parameter eksplisit $satkerId / $targetSatkerId dari parent view / include
    $resolvedSatkerId = $satkerId ?? $targetSatkerId ?? null;

    // 2. Jika belum ada, cek dari objek $satker jika tersedia di view
    if (!$resolvedSatkerId && isset($satker)) {
        if (is_object($satker) && isset($satker->id)) {
            $resolvedSatkerId = $satker->id;
        } elseif (is_numeric($satker)) {
            $resolvedSatkerId = (int) $satker;
        }
    }

    // 3. Jika belum ada, periksa request parameter 'satker_id' (misal cetak via Admin: ?satker_id=19)
    if (!$resolvedSatkerId && request()->filled('satker_id') && is_numeric(request('satker_id'))) {
        $resolvedSatkerId = (int) request('satker_id');
    }

    // 4. Jika belum ada dan ada nama satker ($satkerName), cari ID satkernya
    if (!$resolvedSatkerId && isset($satkerName) && !empty($satkerName) && strtoupper($satkerName) !== 'BIRO LOGISTIK') {
        $foundSatker = \App\Models\Satker::where('nama_satker', $satkerName)->first();
        if ($foundSatker) {
            $resolvedSatkerId = $foundSatker->id;
        }
    }

    // 5. Fallback ke satker_id user yang sedang login jika user adalah admin satker
    if (!$resolvedSatkerId && auth()->check() && auth()->user()->satker_id) {
        $resolvedSatkerId = auth()->user()->satker_id;
    }

    // Ambil penanda tangan satker tersebut
    $penandaTangan = \App\Models\PenandaTangan::getForPdf($resolvedSatkerId);

    // Fallback: jika satker belum mendaftarkan penanda tangan, gunakan penanda tangan global / default
    if (!$penandaTangan && $resolvedSatkerId) {
        $penandaTangan = \App\Models\PenandaTangan::getForPdf(null);
    }

    $sigMarginTop = $marginTop ?? '35px';
    $sigTtdGap = $ttdGap ?? '45px';
    $sigFontSize = $fontSize ?? '10pt';
@endphp

@if($penandaTangan)
<table style="width: 100%; margin-top: {{ $sigMarginTop }}; border: none; border-collapse: collapse;">
    <tr>
        <td style="width: 75%; border: none;">&nbsp;</td>
        <td style="border: none; text-align: center; vertical-align: top; white-space: nowrap;">
            <div style="display: inline-block; text-align: center;">
                <p style="margin: 0; font-size: {{ $sigFontSize }};">Mataram, {{ \Carbon\Carbon::now()->setTimezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
                <p style="margin: 5px 0 0; font-size: {{ $sigFontSize }};">{{ $penandaTangan->jabatan }}</p>
                @if($penandaTangan->jabatan2)
                    <p style="margin: 0; font-size: {{ $sigFontSize }};">{{ $penandaTangan->jabatan2 }}</p>
                @endif
                
                <div style="margin-top: {{ $sigTtdGap }};">
                    <table style="border-collapse: collapse; margin: 0 auto; width: auto; border: none;">
                        <tr>
                            <td style="border: none; border-bottom: 1px solid #000; text-align: center; padding: 0 5px; line-height: 1.3;">
                                <span style="font-size: {{ $sigFontSize }};">{{ $penandaTangan->nama }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; text-align: center; padding: 1px 0 0; line-height: 1.3;">
                                <span style="font-size: {{ $sigFontSize }};">{{ $penandaTangan->pangkat }} NRP {{ $penandaTangan->nrp }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>
@endif
