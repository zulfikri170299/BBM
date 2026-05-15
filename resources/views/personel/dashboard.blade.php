<x-app-layout>
    @push('styles')
        <style>
            /* Container yang akan di-resize oleh JavaScript */
            .zoom-wrapper {
                width: 100% !important;
                max-width: 400px; /* Lebar maksimal di dashboard */
                height: auto;
                position: relative;
                margin: 0 auto;
                aspect-ratio: 1000 / 620;
            }

            .zoom-scale-container {
                position: absolute;
                top: 0;
                left: 0;
                transform-origin: top left;
            }

            /* === CARD DESIGN === */
            .bbm-card-design {
                width: 1000px;
                height: 620px;
                position: relative;
                border-radius: 40px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
                color: #fff;
                display: flex;
                flex-direction: column;
                border: 1px solid rgba(255, 255, 255, 0.15);
                overflow: hidden;
                font-family: 'Outfit', sans-serif;
            }

            .bbm-card-design::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 100%);
                border-radius: 40px;
                pointer-events: none;
                z-index: 1;
            }

            /* === HEADER SECTION === */
            .bbm-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 40px 60px 0;
                z-index: 2;
            }

            .bbm-logo-box {
                width: 90px;
                height: 90px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .bbm-logo-box img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                filter: drop-shadow(0 4px 6px rgba(0,0,0,0.4));
            }

            .bbm-header-text {
                flex: 1;
                text-align: center;
                padding-top: 5px;
            }

            .bbm-header-text h1 {
                font-size: 58px;
                font-weight: 900;
                letter-spacing: 2px;
                line-height: 1.1;
                margin-bottom: 2px;
                text-transform: uppercase;
                text-shadow: 0 4px 10px rgba(0,0,0,0.6);
            }

            .bbm-header-text h2 {
                font-size: 34px;
                font-weight: 700;
                letter-spacing: 6px;
                color: #f8fafc;
                text-transform: uppercase;
                text-shadow: 0 2px 8px rgba(0,0,0,0.5);
            }

            /* === MAIN CONTENT === */
            .bbm-content {
                display: flex;
                flex: 1;
                align-items: center;
                padding: 10px 60px;
                gap: 50px;
                z-index: 2;
            }

            .bbm-qr-area {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }

            .bbm-qr-box {
                background: #fff;
                padding: 15px;
                border-radius: 20px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.4);
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .bbm-qr-label {
                font-family: monospace;
                font-size: 15px;
                color: rgba(255, 255, 255, 0.6);
                letter-spacing: 3px;
                font-weight: 600;
            }

            .bbm-info-area {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .bbm-info-item {
                margin-bottom: 15px;
            }

            .bbm-label-small {
                font-size: 16px;
                font-weight: 600;
                color: rgba(255, 255, 255, 0.6);
                text-transform: uppercase;
                letter-spacing: 2px;
                margin-bottom: 5px;
            }

            .bbm-val-medium {
                font-size: 36px;
                font-weight: 800;
                text-transform: uppercase;
                line-height: 1.2;
                text-shadow: 0 2px 6px rgba(0,0,0,0.4);
            }

            .bbm-val-xlarge {
                font-size: 76px;
                font-weight: 900;
                text-transform: uppercase;
                line-height: 1;
                letter-spacing: 1px;
                text-shadow: 0 6px 15px rgba(0,0,0,0.6);
                margin-top: 5px;
            }

            /* === FOOTER AREA === */
            .bbm-footer-area {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 60px 45px;
                z-index: 2;
            }

            .bbm-pill {
                background: rgba(15, 23, 42, 0.85);
                padding: 12px 30px;
                border-radius: 50px;
                display: flex;
                align-items: center;
                gap: 12px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(8px);
                box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            }

            .bbm-dot-status {
                width: 14px;
                height: 14px;
                border-radius: 50%;
            }

            .bbm-pertamax .bbm-dot-status { background: #3b82f6; box-shadow: 0 0 12px #3b82f6; }
            .bbm-dex .bbm-dot-status { background: #a855f7; box-shadow: 0 0 12px #a855f7; }

            .bbm-label-text {
                font-size: 22px;
                font-weight: 800;
                color: #fff;
            }

            .bbm-logo-pertamina {
                height: 38px;
                object-fit: contain;
                filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));
            }

            .bbm-ip-text {
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
        </style>
    @endpush

    <div class="p-3 sm:p-6 lg:p-8 space-y-4 sm:space-y-8">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-slate-900">Dashboard</h1>

        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-8 items-start">
            <!-- Personel Card Widget -->
            <div
                class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 sm:p-6 border-b border-slate-100">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Kartu Personel</h3>
                </div>
                <!-- Removed overflow-x-auto as we aim to fit it naturally, but keeping flex-center is good -->
                <div class="p-4 sm:p-6 flex flex-col items-center justify-center bg-slate-50/50">
                    @php
                        $p = auth()->user()->personel;
                    @endphp
                    @if($p)
                        <!-- Kartu BBM -->
                        <div class="zoom-wrapper" id="zoom-wrapper">
                            <div class="zoom-scale-container" id="zoom-scale-container">
                                @php
                                    $bgImage = str_contains(strtolower($p->jenis_bbm ?? ''), 'dex') 
                                        ? 'background pertamina dex.png' 
                                        : 'background pertamax.png';
                                @endphp
                                <div class="bbm-card-design" id="personel-card" style="background: url('{{ asset('images/' . $bgImage) }}?v={{ time() }}') no-repeat center center; background-size: cover;">
                                    <div class="bbm-header">
                                        <div class="bbm-logo-box">
                                            <img src="{{ asset('Lambang_Polda_NTB.png') }}" alt="Polda NTB">
                                        </div>
                                        <div class="bbm-header-text">
                                            <h1>KARTU KENDALI BBM</h1>
                                            <h2>BIRO LOGISTIK</h2>
                                        </div>
                                        <div class="bbm-logo-box">
                                            <img src="{{ asset('rolog.png') }}" alt="Logistik">
                                        </div>
                                    </div>

                                    <div class="bbm-content">
                                        <div class="bbm-qr-area">
                                            <div class="bbm-qr-box">
                                                <div id="p-qrcode"></div>
                                            </div>
                                            <div class="bbm-qr-label">{{ $p->barcode ?? $p->nrp }}</div>
                                        </div>
                                        <div class="bbm-info-area">
                                            <div class="bbm-info-item">
                                                <div class="bbm-label-small">Nama Personel</div>
                                                <div class="bbm-val-medium">{{ strtoupper($p->nama) }}</div>
                                            </div>
                                            <div class="bbm-info-item">
                                                <div class="bbm-val-xlarge">{{ $p->nrp }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bbm-footer-area">
                                        <div class="bbm-pill {{ str_contains(strtolower($p->jenis_bbm ?? ''), 'dex') ? 'bbm-dex' : 'bbm-pertamax' }}">
                                            <div class="bbm-dot-status"></div>
                                            <div class="bbm-label-text">{{ $p->jenis_bbm ?? 'BBM' }}</div>
                                        </div>
                                        <img src="{{ asset('assets/images/mypertamina.png') }}" class="bbm-logo-pertamina" alt="MyPertamina">
                                    </div>

                                    <div class="bbm-ip-text">
                                        {{ request()->getHost() == 'localhost' ? '127.0.0.1' : request()->getHost() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:gap-4 mt-2 sm:mt-6 max-w-[320px] w-full mx-auto">
                            <!-- External Balance Display -->
                            <div
                                class="bg-white border border-slate-200 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm text-center">
                                <div
                                    class="text-[8px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 sm:mb-1">
                                    Sisa Kuota</div>
                                <div class="text-lg sm:text-xl font-extrabold text-slate-800">
                                    {{ number_format($p->saldo, 0, ',', '.') }} L
                                </div>
                            </div>

                            <!-- PIN Display -->
                            <div class="bg-white border border-slate-200 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm text-center relative group cursor-pointer"
                                onclick="togglePin()">
                                <div
                                    class="text-[8px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 sm:mb-1">
                                    PIN Kartu</div>
                                <div class="flex items-center justify-center gap-2">
                                    <span id="pin-display"
                                        class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-widest blur-sm select-none">******</span>
                                    <svg id="pin-icon" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="hidden" id="pin-real" value="{{ $p->pin ?? '123456' }}">
                            </div>
                        </div>

                        <div class="mt-4 sm:mt-6 text-center">
                            <button onclick="downloadCard()"
                                class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2 sm:py-2.5 bg-indigo-600 text-white rounded-lg sm:rounded-xl shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/40 transition-all duration-200 transform hover:-translate-y-0.5 font-medium text-xs sm:text-sm">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Kartu
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction History Table -->
            <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 shadow-sm w-full">
                <div class="p-4 sm:p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Riwayat Transaksi</h3>
                </div>
                @if($transactions->isEmpty())
                    <div class="p-8 sm:p-12 text-center">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-100 rounded-full mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-base text-slate-500 font-medium">Belum ada transaksi</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-slate-50/70">
                                    <th
                                        class="px-4 sm:px-6 py-3 sm:py-3.5 text-left text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-4 sm:px-6 py-3 sm:py-3.5 text-left text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Kendaraan</th>
                                    <th
                                        class="px-4 sm:px-6 py-3 sm:py-3.5 text-right text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Liter</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($transactions as $trx)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td
                                            class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-slate-600">
                                            {{ $trx->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <span
                                                class="text-xs sm:text-sm font-semibold text-slate-800">{{ $trx->kendaraan->no_polisi ?? ($trx->personel->nrp ?? '-') }}</span>
                                            <span
                                                class="text-[10px] sm:text-xs text-slate-400 ml-1">({{ $trx->kendaraan->jenis_bbm ?? ($trx->personel->jenis_bbm ?? '-') }})</span>
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-slate-600 text-right">
                                            {{ $trx->liter }} L
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 sm:p-4 border-t border-slate-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            @if(auth()->user()->personel)
                new QRCode(document.getElementById('p-qrcode'), {
                    text: '{{ auth()->user()->personel->barcode ?? auth()->user()->personel->nrp }}',
                    width: 160,
                    height: 160,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });

                function setDashboardCardScale() {
                    const wrapper = document.getElementById('zoom-wrapper');
                    const scaleContainer = document.getElementById('zoom-scale-container');
                    if(wrapper && scaleContainer) {
                        const availableWidth = wrapper.clientWidth;
                        if(availableWidth === 0) {
                            setTimeout(setDashboardCardScale, 100);
                            return;
                        }
                        const scale = availableWidth / 1000;
                        scaleContainer.style.transform = `scale(${scale})`;
                        wrapper.style.height = `${620 * scale}px`;
                    }
                }
                window.addEventListener('resize', setDashboardCardScale);
                document.addEventListener('turbo:load', setDashboardCardScale);
                document.addEventListener('turbo:render', setDashboardCardScale);
                setTimeout(setDashboardCardScale, 50);

                function downloadCard() {
                    const btn = event.currentTarget;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '⏳ Memproses...';
                    btn.disabled = true;

                    const card = document.getElementById('personel-card');
                    const scaleContainer = document.getElementById('zoom-scale-container');
                    const originalTransform = scaleContainer.style.transform;
                    
                    // Kembalikan skala ke asli untuk menangkap resolusi tinggi
                    scaleContainer.style.transform = 'scale(1)';

                    html2canvas(card, {
                        scale: 2,
                        backgroundColor: null,
                        useCORS: true,
                        logging: false
                    }).then(canvas => {
                        scaleContainer.style.transform = originalTransform;
                        const link = document.createElement('a');
                        link.download = 'Kartu-Personel-{{ auth()->user()->personel->nrp }}.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }).catch(err => {
                        scaleContainer.style.transform = originalTransform;
                        alert('Gagal memproses gambar');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                }

                function togglePin() {
                    const display = document.getElementById('pin-display');
                    const realPin = document.getElementById('pin-real').value;
                    const icon = document.getElementById('pin-icon');

                    if (display.classList.contains('blur-sm')) {
                        display.classList.remove('blur-sm');
                        display.textContent = realPin;
                        // Change icon to eye-off
                        // Simplified for now
                    } else {
                        display.classList.add('blur-sm');
                        display.textContent = '******';
                    }
                }
            @endif
        </script>
    @endpush
</x-app-layout>