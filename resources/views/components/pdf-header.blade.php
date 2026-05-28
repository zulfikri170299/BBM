@php
    $satkerName = 'BIRO LOGISTIK'; // Default for Super Admin
    
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin_satker' || $user->role === 'petugas') {
            if ($user->satker) {
                $satkerName = strtoupper($user->satker->nama_satker);
            }
        }
    }
    
    // Allow overriding from the include call if needed
    if (isset($satker)) {
        if (is_object($satker)) {
            $satkerName = strtoupper($satker->nama_satker);
        } elseif (is_string($satker)) {
            $satkerName = strtoupper($satker);
        }
    }
@endphp

<div style="text-align: left; margin-bottom: 25px; font-family: sans-serif;">
    <div style="display: inline-block; text-align: center; font-weight: bold; font-size: 11pt; line-height: 1.3;">
        <img src="{{ public_path('TRIBRATA.png') }}" style="width: 85px; height: auto; margin-bottom: 5px; display: block; margin-left: auto; margin-right: auto;">
        <p style="margin: 0;">KEPOLISIAN NEGARA REPUBLIK INDONESIA</p>
        <p style="margin: 0;">DAERAH NUSA TENGGARA BARAT</p>
        <p style="margin: 0; border-bottom: 2px solid #000; padding-bottom: 1px;">{{ $satkerName }}</p>
    </div>
</div>
