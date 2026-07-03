<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara - {{ $satker }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0;
            padding: 30px 40px;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .kop-surat {
            width: 360px;
            text-align: center;
            border-bottom: 2.5px solid black;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }
        .kop-surat-inner {
            border-bottom: 1px solid black;
            padding-bottom: 5px;
        }
        .kop-text {
            line-height: 1.15;
            font-size: 10.5pt;
        }
        .content {
            text-align: justify;
        }
        table.signature {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        .signature td {
            width: 50%;
            vertical-align: top;
            height: 150px;
        }
        .signature-space {
            height: 80px;
        }
    </style>
</head>
<body>
    <table style="width: 100%; margin-bottom: 2px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 320px; text-align: center; vertical-align: top;">
                <div style="border-bottom: 1.5px solid black; padding-bottom: 5px; line-height: 1.15; font-size: 10.5pt;">
                    KEPOLISIAN NEGARA REPUBLIK INDONESIA<br>
                    DAERAH NUSA TENGGARA BARAT<br>
                    BIRO LOGISTIK
                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>
    
    <div class="text-center" style="margin-bottom: 15px; margin-top: 30px;">
        <div style="display: inline-block; text-align: center; line-height: 1.1;">
            <div style="border-bottom: 1.5px solid black; padding-bottom: 0px; font-size: 11.5pt;">BERITA ACARA</div>
            <div style="padding-top: 1px;">Nomor: BA- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/{{ $bulang_angka_romawi }}/LOG.5.15.1./{{ $tahun }}/Rolog</div>
        </div>
    </div>

    <div class="content">
        <p>Pada hari Ini {{ $hari_huruf }} tanggal {{ $tanggal_huruf }} tahun {{ $tahun }}, yang bertanda tangan di bawah ini:</p>

        <table style="width: 100%; margin-left: 20px; margin-bottom: 15px;">
            <tr><td style="width: 120px;">Nama</td><td>: {{ $settings['ba_pihak_1_nama'] ?? '-' }}</td></tr>
            <tr><td>Pangkat</td><td>: {{ $settings['ba_pihak_1_pangkat'] ?? '-' }}</td></tr>
            <tr><td>NRP</td><td>: {{ $settings['ba_pihak_1_nrp'] ?? '-' }}</td></tr>
            <tr><td>Jabatan</td><td>: {{ $settings['ba_pihak_1_jabatan'] ?? '-' }}</td></tr>
        </table>

        <p>Bertindak untuk dan atas nama Satker {{ $satker }} yang selanjutnya disebut Pihak Kesatu</p>

        <table style="width: 100%; margin-left: 20px; margin-bottom: 15px;">
            <tr><td style="width: 120px;">Nama</td><td>: </td></tr>
            <tr><td>Pangkat</td><td>: </td></tr>
            <tr><td>NRP/NIP</td><td>: </td></tr>
            <tr><td>Jabatan</td><td>: </td></tr>
        </table>

        <p style="text-indent: 40px;">
            Bertindak dan atas nama Satker {{ $satker }} Polda NTB yang selanjutnya disebut Pihak kedua dengan ini menyatakan bahwa Pihak Kesatu telah menyerahkan kartu Ranjen/BBM Bulan {{ $bulan }} Tahun {{ $tahun }} kepada Pihak kedua berupa Pertamax sejumlah {{ $p_total }} Liter dan Pertamina Dex sejumlah {{ $d_total }} Liter.
        </p>

        <p style="margin-bottom: 40px;">
            Demikian Berita Acara Serah Terima Ranjen BBM ini dibuat dengan sebenar benarnya untuk dapat dipergunakan sebagaimana mestinya.
        </p>

        <div style="text-align: right; margin-bottom: 20px; padding-right: 40px;">
            Mataram, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $bulan }} {{ $tahun }}
        </div>

        <table class="signature">
            <tr>
                <td>
                    PIHAK KEDUA
                    <div class="signature-space"></div>
                    <span class="underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br>
                    &nbsp;
                </td>
                <td>
                    PIHAK KESATU
                    <div class="signature-space"></div>
                    <span class="underline">{{ $settings['ba_pihak_1_nama'] ?? '-' }}</span><br>
                    {{ $settings['ba_pihak_1_pangkat'] ?? '-' }} NRP {{ $settings['ba_pihak_1_nrp'] ?? '-' }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
