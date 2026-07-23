<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu Personel - {{ $personel->nrp }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Container yang akan di-resize oleh JavaScript */
        .zoom-wrapper {
            width: 1000px;
            height: 620px;
            position: relative;
            margin: 0 auto;
            transition: width 0.1s, height 0.1s;
        }

        /* === CARD DESIGN === */
        .card {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
            width: 1000px;
            height: 620px;
            position: absolute;
            top: 0;
            left: 0;
            transform-origin: top left;
            @php
                $bgImage = str_contains(strtolower($personel->jenis_bbm), 'dex') 
                    ? 'background pertamina dex.png' 
                    : 'background pertamax.png';
            @endphp
            background: url('{{ asset('images/' . $bgImage) }}?v={{ time() }}') no-repeat center center;
            background-size: cover;
            border-radius: 40px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
            color: #fff;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255, 255, 255, 0.15);
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 100%);
            border-radius: 40px;
            pointer-events: none;
            z-index: 1;
        }

        /* === HEADER SECTION === */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 40px 60px 0;
            z-index: 2;
        }

        .logo-box {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.4));
        }

        .header-text {
            flex: 1;
            text-align: center;
            padding-top: 5px;
        }

        .header-text h1 {
            font-size: 58px;
            font-weight: 900;
            letter-spacing: 2px;
            line-height: 1.1;
            margin-bottom: 2px;
            text-transform: uppercase;
            text-shadow: 0 4px 10px rgba(0,0,0,0.6);
        }

        .header-text h2 {
            font-size: 34px;
            font-weight: 700;
            letter-spacing: 6px;
            color: #f8fafc;
            text-transform: uppercase;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }

        /* === MAIN CONTENT === */
        .content {
            display: flex;
            flex: 1;
            align-items: center;
            padding: 10px 60px;
            gap: 50px;
            z-index: 2;
        }

        .qr-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .qr-box {
            background: #fff;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-label {
            font-family: monospace;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 3px;
            font-weight: 600;
        }

        .info-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .label-small {
            font-size: 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .val-medium {
            font-size: 36px;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.2;
            text-shadow: 0 2px 6px rgba(0,0,0,0.4);
        }

        .val-xlarge {
            font-size: 76px;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1;
            letter-spacing: 1px;
            text-shadow: 0 6px 15px rgba(0,0,0,0.6);
            margin-top: 5px;
        }

        /* === FOOTER AREA === */
        .footer-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 60px 45px;
            z-index: 2;
        }

        .pill-bbm {
            background: rgba(15, 23, 42, 0.85);
            padding: 12px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
box-shadow: 0 8px 20px rgba(0,0,0,0.4);
        }

        .dot-status {
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        .pertamax .dot-status { background: #3b82f6; box-shadow: 0 0 12px #3b82f6; }
        .dex .dot-status { background: #a855f7; box-shadow: 0 0 12px #a855f7; }

        .bbm-label {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
        }

        .logo-pertamina {
            height: 38px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));
        }

        .ip-text {
            position: absolute;
            bottom: 12px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 24px;
            font-weight: 900;
            color: #3b82f6;
            letter-spacing: 3px;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }

        /* === MOBILE MENU BUTTON === */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            z-index: 100;
            cursor: pointer;
            font-size: 20px;
            align-items: center;
            justify-content: center;
box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            transition: background 0.2s;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(30, 41, 59, 1);
        }

        /* === CONTROLS === */
        .action-buttons-wrapper {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
            max-width: 1000px;
            z-index: 10;
        }

        .controls {
            display: flex;
            gap: 15px;
        }

        .bottom-controls {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 14px 30px;
            border-radius: 16px;
            border: none;
            font-family: inherit;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.2s, opacity 0.2s;
        }

        .btn-print { background: #4f46e5; color: #fff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4); }
        .btn-dl { background: #10b981; color: #fff; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); }
        .btn-back { background: #334155; color: #fff; }

        .btn:hover { transform: translateY(-3px); opacity: 0.9; }

        /* Tampilan tombol responsif di HP (Dropdown Menu & Bottom Back Button) */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }
            .action-buttons-wrapper {
                margin-top: 20px;
            }
            .controls {
                display: none; /* hidden secara default di mobile */
                position: fixed;
                top: 75px;
                right: 20px;
                background: rgba(15, 23, 42, 0.95);
padding: 15px;
                border-radius: 16px;
                flex-direction: column;
                gap: 10px;
                width: 220px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.6);
                border: 1px solid rgba(255,255,255,0.15);
                animation: fadeIn 0.2s ease-out;
                margin-top: 0;
            }
            .controls.show {
                display: flex;
            }
            .controls .btn {
                width: 100%;
                padding: 14px 20px;
                justify-content: flex-start;
                font-size: 14px;
            }
            
            .bottom-controls {
                width: 100%;
                justify-content: center;
                padding: 0 10px;
            }
            .bottom-controls .btn-back {
                width: 100%;
                padding: 16px 20px;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media print {
            body { background: transparent; padding: 0; }
            .action-buttons-wrapper, .mobile-menu-btn { display: none !important; }
            .zoom-wrapper { 
                width: 1000px !important; 
                height: 620px !important; 
                display: block; 
            }
            .card { 
                transform: none !important;
                box-shadow: none; 
                border: none;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
                position: relative;
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="menu-btn" onclick="toggleMenu()">
        ⋮
    </button>

    <div class="zoom-wrapper" id="zoom-wrapper">
        <div class="card" id="kartu-bbm">
            <!-- Header -->
            <div class="header">
                <div class="logo-box">
                    <img src="{{ asset('Lambang_Polda_NTB.png') }}" alt="Polda NTB">
                </div>
                <div class="header-text">
                    <h1>KARTU KENDALI BBM</h1>
                    <h2>BIRO LOGISTIK</h2>
                </div>
                <div class="logo-box">
                    <img src="{{ asset('rolog.png') }}" alt="Logistik">
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="qr-area">
                    <div class="qr-box">
                        <div id="qrcode"></div>
                    </div>
                    <div class="qr-label">{{ $personel->barcode ?? $personel->nrp }}</div>
                </div>
                <div class="info-area">
                    <div class="info-item">
                        <div class="label-small">Nama Personel</div>
                        <div class="val-medium">{{ strtoupper($personel->nama) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="val-xlarge">{{ $personel->nrp }}</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-area">
                <div class="pill-bbm {{ str_contains(strtolower($personel->jenis_bbm), 'dex') ? 'dex' : 'pertamax' }}">
                    <div class="dot-status"></div>
                    <div class="bbm-label">{{ $personel->jenis_bbm ?? 'BBM' }}</div>
                </div>
                <img src="{{ asset('assets/images/mypertamina.png') }}" class="logo-pertamina" alt="MyPertamina">
            </div>

            <!-- IP -->
            <div class="ip-text">
                {{ request()->getHost() == 'localhost' ? '127.0.0.1' : request()->getHost() }}
            </div>
        </div>
    </div>

    <!-- Controls Container -->
    <div class="action-buttons-wrapper">
        <!-- Cetak & Download (Jadi dropdown di mobile) -->
        <div class="controls" id="mobile-menu">
            <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Kartu</button>
            <button onclick="downloadKartu()" class="btn btn-dl" id="btn-download">📥 Download Gambar</button>
        </div>
        
        <!-- Tombol Kembali (Tetap di bawah kartu di mobile) -->
        <div class="bottom-controls">
            <button onclick="window.location.href='{{ auth()->user()->role === 'super_admin' ? route('admin.personels.index') : route('satker.personels.index') }}'" class="btn btn-back">← Kembali</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        new QRCode(document.getElementById('qrcode'), {
            text: '{{ $personel->barcode ?? $personel->nrp }}',
            width: 160,
            height: 160,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        // Script cerdas untuk menyesuaikan skala kartu secara tepat ke layar tanpa memotong
        function setResponsiveScale() {
            const wrapper = document.getElementById('zoom-wrapper');
            const card = document.getElementById('kartu-bbm');
            
            // 40px adalah kompensasi dari padding body (20px kiri + 20px kanan)
            let availableWidth = window.innerWidth - 40;
            
            // Jika di laptop/desktop dengan lebar lebih dari 1000px, batas ke 1000px
            if (availableWidth > 1000) {
                availableWidth = 1000;
            }
            
            const scale = availableWidth / 1000;
            
            card.style.transform = `scale(${scale})`;
            wrapper.style.width = `${1000 * scale}px`;
            wrapper.style.height = `${620 * scale}px`;
        }

        window.addEventListener('resize', setResponsiveScale);
        document.addEventListener('DOMContentLoaded', setResponsiveScale);
        setResponsiveScale();

        // Fungsi toggle menu di mobile
        function toggleMenu() {
            document.getElementById('mobile-menu').classList.toggle('show');
        }

        // Tutup menu jika klik di luar area menu
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobile-menu');
            const btn = document.getElementById('menu-btn');
            if (window.innerWidth <= 768 && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('show');
            }
        });

        function downloadKartu() {
            const btn = document.getElementById('btn-download');
            btn.innerHTML = '⏳ Memproses...';
            btn.disabled = true;

            const card = document.getElementById('kartu-bbm');
            const originalTransform = card.style.transform;
            
            // Kembalikan ke ukuran 1000x620 asli agar hasil download resolusi tinggi
            card.style.transform = 'scale(1)';

            // Sembunyikan menu saat download jika terbuka
            document.getElementById('mobile-menu').classList.remove('show');

            html2canvas(card, {
                scale: 2,
                useCORS: true,
                backgroundColor: null,
            }).then(canvas => {
                // Kembalikan skala responsif untuk tampilan layar
                card.style.transform = originalTransform;

                const link = document.createElement('a');
                link.download = 'Kartu_Personel_{{ $personel->nrp }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();

                btn.innerHTML = '📥 Download Gambar';
                btn.disabled = false;
            }).catch(err => {
                card.style.transform = originalTransform;
                Swal.fire('Gagal', 'Terjadi kesalahan saat memproses gambar.', 'error');
                btn.innerHTML = '📥 Download Gambar';
                btn.disabled = false;
            });
        }
    </script>
</body>

</html>