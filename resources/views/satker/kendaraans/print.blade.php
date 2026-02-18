<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu BBM - {{ $kendaraan->no_polisi }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f1f5f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* === CARD DESIGN === */
        .card {
            width: 100%;
            max-width: 550px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            color: #fff;
            position: relative;
        }

        /* Shared pseudo-elements layout */
        .card::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
        }

        .card::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
        }

        /* === CARD VARIANTS === */

        /* RED THEME (Pertamax, Pertalite) */
        .card-red {
            background: linear-gradient(135deg, #d50000 0%, #7f0000 60%, #000000 100%);
        }

        .card-red::before {
            background: radial-gradient(circle, rgba(255, 50, 50, 0.5) 0%, transparent 70%);
        }

        .card-red::after {
            background: radial-gradient(circle, rgba(220, 0, 0, 0.4) 0%, transparent 70%);
        }

        /* YELLOW THEME (Dex, Solar) */
        .card-yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 60%, #000000 100%);
        }

        .card-yellow::before {
            background: radial-gradient(circle, rgba(251, 191, 36, 0.5) 0%, transparent 70%);
        }

        .card-yellow::after {
            background: radial-gradient(circle, rgba(245, 158, 11, 0.4) 0%, transparent 70%);
        }

        /* === INTERNAL CARD STYLES === */
        .card-header {
            padding: 20px 24px 0;
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .header-info {
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-header .label {
            font-size: 32px;
            /* Diperbesar sesuai permintaan */
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
            font-weight: 800;
            line-height: 1.1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .card-header .satker-name {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.95);
            margin-top: 2px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .card-footer .footer-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-end;
        }

        .side-id-section {
            display: none;
        }

        .side-id-text {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 0.5px;
            text-align: center;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .card-header .header-logo-left,
        .card-header .header-logo-right {
            flex-shrink: 0;
            width: 60px;
            display: flex;
            align-items: center;
        }

        .card-header .header-logo-left {
            justify-content: flex-start;
        }

        .card-header .header-logo-right {
            justify-content: flex-end;
        }

        .card-header img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4));
        }

        .card-body {
            padding: 20px 24px;
            display: flex;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .qr-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .qr-wrapper {
            background: #fff;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .qr-code-text {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.4);
            font-family: monospace;
            letter-spacing: 1px;
        }

        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 12px;
        }

        .info-item .info-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
        }

        .info-item .info-value {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            margin-top: 1px;
        }

        .website-link-text {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-top: 15px;
            letter-spacing: 1px;
            display: block;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .card-footer {
            padding: 10px 24px 14px;
            background: rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            /* Perkecil padding/gap sesuai permintaan */
        }

        .footer-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .footer-bottom-row {
            text-align: center;
            width: 100%;
        }

        .footer-website-link {
            font-size: 18px;
            font-weight: 700;
            color: #1e90ff;
            /* Mengubah warna menjadi biru */
            letter-spacing: 1.2px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
            text-transform: lowercase;
        }

        .bbm-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
        }

        .bbm-pertalite {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        .bbm-pertamax {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
        }

        .bbm-solar {
            background: rgba(245, 158, 11, 0.2);
            color: #fcd34d;
        }

        .bbm-dexlite {
            background: rgba(168, 85, 247, 0.2);
            color: #fcd34d;
        }

        .bbm-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .dot-pertalite {
            background: #10b981;
        }

        .dot-pertamax {
            background: #3b82f6;
        }

        .dot-solar {
            background: #f59e0b;
        }

        .dot-dexlite {
            background: #a855f7;
        }

        .card-footer .date {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.35);
        }

        /* === CONTROLS (hidden on print) === */
        .controls {
            margin-top: 24px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-print {
            background: linear-gradient(135deg, #4338ca, #6366f1);
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .btn-download {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
        }

        .btn-back {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-back:hover {
            background: #cbd5e1;
        }

        /* === MOBILE RESPONSIVENESS === */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .card-header {
                padding: 15px 15px 0;
            }

            .card-header .label {
                font-size: 20px;
            }

            .card-header .satker-name {
                font-size: 14px;
                margin-top: 0;
            }

            .card-header .header-logo-left,
            .card-header .header-logo-right {
                width: 45px;
            }

            .card-header img {
                width: 40px;
                height: 40px;
            }

            .card-body {
                gap: 12px;
                padding: 12px 15px;
            }

            .qr-wrapper {
                padding: 5px;
            }

            #qrcode img {
                width: 70px !important;
                height: 70px !important;
            }

            .qr-code-text {
                font-size: 7px;
                margin-top: 2px;
            }

            .info-section {
                gap: 8px;
            }

            .info-item .info-label {
                font-size: 8px;
                letter-spacing: 1px;
            }

            .info-item .info-value {
                font-size: 13px;
                line-height: 1.2;
            }

            .website-link-text {
                font-size: 18px;
                margin-top: 6px;
            }

            .card-footer {
                padding: 8px 15px 12px;
                gap: 4px;
            }

            .footer-website-link {
                font-size: 14px;
                letter-spacing: 0.8px;
            }

            .bbm-badge {
                padding: 3px 8px;
                font-size: 10px;
            }

            .footer-right img {
                height: 14px !important;
            }
        }

        /* === PRINT STYLES === */
        @media print {
            body {
                background: #fff;
                padding: 0;
                min-height: auto;
                justify-content: flex-start;
                align-items: flex-start;
            }

            .controls {
                display: none !important;
            }

            .card {
                width: 550px !important;
                /* Force original size for print */
                box-shadow: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    @php
        $cardTheme = match ($kendaraan->jenis_bbm) {
            'Pertamina Dex', 'Dexlite', 'Solar' => 'yellow',
            default => 'red', // Pertamax, Pertalite defaults to Red
        };
    @endphp

    <div class="card card-{{ $cardTheme }}" id="kartu-bbm">
        <div class="card-header">
            <div class="header-logo-left">
                <img src="{{ asset('Lambang_Polda_NTB.png') }}" alt="Logo Polda NTB">
            </div>
            <div class="header-info">
                <div class="label">Kartu Kendali BBM</div>
                <div class="satker-name">{{ $kendaraan->satker->nama_satker }}</div>
            </div>
            <div class="header-logo-right">
                <img src="{{ asset('rolog.png') }}" alt="Logo Logistik">
            </div>
        </div>

        <div class="card-body">
            <div class="qr-section">
                <div class="qr-wrapper">
                    <div id="qrcode"></div>
                </div>
                <div class="qr-code-text">{{ $kendaraan->barcode }}</div>
            </div>
            <div class="info-section">
                <div class="info-item">
                    <div class="info-label">Jenis Kendaraan</div>
                    <div class="info-value">{{ $kendaraan->jenis_kendaraan }}</div>
                    <div class="website-link-text">{{ $kendaraan->no_polisi }}</div>
                </div>
            </div>
            <div class="side-id-section">
            </div>
        </div>

        <div class="card-footer">
            @php
                $bbmClass = match ($kendaraan->jenis_bbm) {
                    'Pertalite' => 'pertalite',
                    'Pertamax' => 'pertamax',
                    'Solar' => 'solar',
                    'Dexlite' => 'dexlite',
                    default => 'pertalite',
                };
            @endphp
            <div class="footer-top-row">
                <div class="bbm-badge bbm-{{ $bbmClass }}">
                    <span class="bbm-dot dot-{{ $bbmClass }}"></span>
                    {{ $kendaraan->jenis_bbm }}
                </div>
                <div class="footer-right">
                    <img src="{{ asset('assets/images/mypertamina.png') }}" alt="MyPertamina"
                        style="height: 18px; opacity: 0.8;">
                </div>
            </div>
            <div class="footer-bottom-row">
                <div class="footer-website-link">spbp-poldantb.com</div>
            </div>
        </div>
    </div>

    <div class="controls">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak Kartu
        </button>
        <button onclick="downloadKartu()" class="btn btn-download" id="btn-download">
            📥 Download Gambar
        </button>
        <button onclick="window.close(); history.back();" class="btn btn-back">
            ← Kembali
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        new QRCode(document.getElementById('qrcode'), {
            text: '{{ $kendaraan->barcode }}',
            width: 100,
            height: 100,
            colorDark: '#1e293b',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        function downloadKartu() {
            const btn = document.getElementById('btn-download');
            btn.textContent = '⏳ Memproses...';
            btn.disabled = true;

            const kartu = document.getElementById('kartu-bbm');

            html2canvas(kartu, {
                scale: 3,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
                borderRadius: 20,
            }).then(function (canvas) {
                const link = document.createElement('a');
                link.download = 'Kartu_BBM_{{ $kendaraan->no_polisi }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();

                btn.textContent = '📥 Download Gambar';
                btn.disabled = false;
            }).catch(function (err) {
                alert('Gagal membuat gambar. Silakan coba lagi.');
                btn.textContent = '📥 Download Gambar';
                btn.disabled = false;
                console.error(err);
            });
        }
    </script>
</body>

</html>