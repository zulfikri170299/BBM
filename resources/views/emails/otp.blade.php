<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP SIMAK BBM</title>
</head>
<body style="font-family: 'Outfit', sans-serif; background-color: #f8fafc; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #4338ca; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">SIMAK BBM</h1>
        </div>
        <div style="padding: 32px; text-align: center;">
            <h2 style="color: #1e293b; margin-top: 0;">Kode Verifikasi OTP</h2>
            <p style="color: #64748b; font-size: 16px;">Berikut adalah kode OTP Anda untuk melakukan Top-up Saldo. Kode ini berlaku selama 5 menit.</p>
            
            <div style="margin: 32px 0; padding: 16px; background-color: #f1f5f9; border-radius: 12px; display: inline-block;">
                <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4338ca;">{{ $otp }}</span>
            </div>
            
            <p style="color: #94a3b8; font-size: 14px;">Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.</p>
        </div>
        <div style="background-color: #f8fafc; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} SIMAK BBM. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
