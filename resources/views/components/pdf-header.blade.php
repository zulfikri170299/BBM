@php
    $displaySatkerName = 'BIRO LOGISTIK'; // Default for Super Admin
    
    // Check if passed from parent view or include
    if (isset($satkerName) && !empty($satkerName)) {
        $displaySatkerName = strtoupper($satkerName);
    } elseif (isset($satker)) {
        if (is_object($satker)) {
            $displaySatkerName = strtoupper($satker->nama_satker);
        } elseif (is_string($satker)) {
            $displaySatkerName = strtoupper($satker);
        }
    } else {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'admin_satker' || $user->role === 'petugas') {
                if ($user->satker) {
                    $displaySatkerName = strtoupper($user->satker->nama_satker);
                }
            }
        }
    }

    $headerLogoWidth = $logoWidth ?? '120px';
    $headerMarginBottom = $headerMargin ?? '25px';
    $headerTextSize = $headerFontSize ?? '11pt';
@endphp

<div style="text-align: left; margin-bottom: {{ $headerMarginBottom }}; font-family: sans-serif;">
    <div style="display: inline-block; text-align: center; font-weight: bold; font-size: {{ $headerTextSize }}; line-height: 1.3;">
        <img src="{{ public_path('TRIBRATA.png') }}" style="width: {{ $headerLogoWidth }}; height: auto; margin-bottom: 5px; display: block; margin-left: auto; margin-right: auto;">
        <p style="margin: 0;">KEPOLISIAN NEGARA REPUBLIK INDONESIA</p>
        <p style="margin: 0;">DAERAH NUSA TENGGARA BARAT</p>
        <p style="margin: 0; border-bottom: 2px solid #000; padding-bottom: 1px;">{{ $displaySatkerName }}</p>
    </div>
</div>
