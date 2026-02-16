<x-app-layout>
    @push('styles')
    <style>
        /* === ID CARD STYLES === */
        .id-card {
            width: 320px; /* Reduced to 320px */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px -5px rgba(0,0,0,0.3);
            color: #fff;
            position: relative;
            margin: 0 auto;
            font-family: 'Outfit', sans-serif;
            background-color: #000;
        }

        /* Shared pseudo-elements */
        .id-card::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .id-card::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        /* VARIANTS */
        .id-card-red {
            background: linear-gradient(135deg, #d50000 0%, #7f0000 60%, #000000 100%);
        }
        .id-card-red::before { background: radial-gradient(circle, rgba(255, 50, 50, 0.5) 0%, transparent 70%); }
        .id-card-red::after { background: radial-gradient(circle, rgba(220, 0, 0, 0.4) 0%, transparent 70%); }

        .id-card-yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 60%, #000000 100%);
        }
        .id-card-yellow::before { background: radial-gradient(circle, rgba(251, 191, 36, 0.5) 0%, transparent 70%); }
        .id-card-yellow::after { background: radial-gradient(circle, rgba(245, 158, 11, 0.4) 0%, transparent 70%); }

        /* HEADER */
        .id-card-header {
            padding: 12px 14px 0;
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .id-header-info {
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .id-label {
            font-size: 18px; /* Reduced */
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
            font-weight: 800;
            line-height: 1.1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .id-satker-name {
            font-size: 11px; /* Reduced */
            color: rgba(255,255,255,0.95);
            margin-top: 1px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .id-header-logo-left, .id-header-logo-right {
            flex-shrink: 0;
            width: 35px;
            display: flex;
            align-items: center;
        }
        .id-header-logo-left { justify-content: flex-start; }
        .id-header-logo-right { justify-content: flex-end; }

        .id-card-header img {
            width: 30px;
            height: 30px;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }

        /* BODY */
        .id-card-body {
            padding: 12px 14px;
            display: flex;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        .id-qr-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .id-qr-wrapper {
            background: #fff;
            padding: 4px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .id-qr-code-text {
            font-size: 7px;
            color: rgba(255,255,255,0.4);
            font-family: monospace;
            letter-spacing: 1px;
        }
        .id-info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }
        .id-info-item .info-label {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }
        .id-info-item .info-value {
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            margin-top: 1px;
        }
        .id-website-link {
            font-size: 14px; /* Reduced */
            font-weight: 800;
            color: #fff;
            margin-top: 8px;
            letter-spacing: 1px;
            display: block;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* FOOTER */
        .id-card-footer {
            padding: 6px 14px 10px;
            background: rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .id-footer-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .id-footer-bottom {
            text-align: center;
            width: 100%;
        }
        .id-footer-link {
            font-size: 11px; /* Reduced */
            font-weight: 700;
            color: #1e90ff; /* Blue */
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.4);
            text-transform: lowercase;
        }

        .id-bbm-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 12px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2);
            font-size: 8px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
        }
        .id-bbm-pertalite { background: rgba(255,255,255,0.15); color: #fff; }
        .id-bbm-pertamax { background: rgba(255,255,255,0.15); color: #fff; }
        .id-bbm-solar { background: rgba(255,255,255,0.15); color: #fff; }
        .id-bbm-dexlite { background: rgba(255,255,255,0.15); color: #fff; }
        
        .id-bbm-dot { width: 4px; height: 4px; border-radius: 50%; }
        .id-dot-pertalite { background: #2ecc71; box-shadow: 0 0 4px #2ecc71; }
        .id-dot-pertamax { background: #3498db; box-shadow: 0 0 4px #3498db; }
        .id-dot-solar { background: #f1c40f; box-shadow: 0 0 4px #f1c40f; }
        .id-dot-dexlite { background: #9b59b6; box-shadow: 0 0 4px #9b59b6; }

        /* RESPONSIVE MOBILE STYLES */
        @media (max-width: 640px) {
            .id-card {
                width: 100% !important;
                border-radius: 12px;
                box-shadow: 0 5px 15px -3px rgba(0,0,0,0.3);
            }
            /* ... (keep existing media query scaling or let it be handled by natural scaling of 320px base) */
        }
    </style>
    @endpush

    <div class="p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Personel Overview</h1>
            <p class="mt-1 text-slate-500">Halo, {{ Auth::user()->name }}! Berikut informasi akun Anda.</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
            <!-- Personel Card Widget -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Kartu Personel</h3>
                </div>
                <!-- Removed overflow-x-auto as we aim to fit it naturally, but keeping flex-center is good -->
                <div class="p-6 flex flex-col items-center justify-center bg-slate-50/50">
                    @php
                        $p = auth()->user()->personel;
                    @endphp
                    @if($p)
                        @php
                            $cardTheme = match($p->jenis_bbm) {
                                'Pertamina Dex', 'Dexlite', 'Solar' => 'yellow',
                                default => 'red',
                            };
                            $bbmClass = match($p->jenis_bbm) {
                                'Pertalite' => 'pertalite',
                                'Pertamax' => 'pertamax',
                                'Solar' => 'solar',
                                'Dexlite' => 'dexlite',
                                default => 'pertalite',
                            };
                        @endphp
                        
                        <!-- Kartu BBM (Style matched to restored card.blade.php) -->
                        <div class="id-card id-card-{{ $cardTheme }}" id="personel-card">
                            <div class="id-card-header">
                                <div class="id-header-logo-left">
                                     <img src="{{ asset('Lambang_Polda_NTB.png') }}" alt="Logo Polda">
                                </div>
                                <div class="id-header-info">
                                    <div class="id-label">Kartu Kendali BBM</div>
                                    <div class="id-satker-name">{{ $p->satker->nama_satker }}</div>
                                </div>
                                <div class="id-header-logo-right">
                                    <img src="{{ asset('rolog.png') }}" alt="Logo Logistik">
                                </div>
                            </div>

                            <div class="id-card-body">
                                <div class="id-qr-section">
                                    <div class="id-qr-wrapper">
                                        <div id="p-qrcode"></div>
                                    </div>
                                    <div class="id-qr-code-text">{{ $p->barcode ?? $p->nrp }}</div>
                                </div>
                                <div class="id-info-section">
                                    <div class="id-info-item">
                                        <div class="info-label">Nama Personel</div>
                                        <div class="info-value">{{ strtoupper($p->nama) }}</div>
                                        <div class="id-website-link">{{ $p->nrp }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="id-card-footer">
                                <div class="id-footer-top">
                                    <div class="id-bbm-badge id-bbm-{{ $bbmClass }}">
                                        <span class="id-bbm-dot id-dot-{{ $bbmClass }}"></span>
                                        {{ $p->jenis_bbm ?? 'BBM' }}
                                    </div>
                                    <img src="{{ asset('assets/images/mypertamina.png') }}" alt="MyPertamina" style="height: 14px; opacity: 0.8;">
                                </div>
                                <div class="id-footer-bottom">
                                    <div class="id-footer-link">spbp-poldantb.com</div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6 max-w-[320px] w-full mx-auto">
                            <!-- External Balance Display -->
                            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sisa Kuota</div>
                                <div class="text-xl font-extrabold text-slate-800">{{ number_format($p->saldo, 0, ',', '.') }} L</div>
                            </div>
                            
                            <!-- PIN Display -->
                            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center relative group cursor-pointer" onclick="togglePin()">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">PIN Kartu</div>
                                <div class="flex items-center justify-center gap-2">
                                    <span id="pin-display" class="text-xl font-extrabold text-slate-800 tracking-widest blur-sm select-none">******</span>
                                    <svg id="pin-icon" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                                <input type="hidden" id="pin-real" value="{{ $p->pin ?? '123456' }}">
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <button onclick="downloadCard()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white rounded-xl shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/40 transition-all duration-200 transform hover:-translate-y-0.5 font-medium text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Kartu
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction History Table -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm w-full">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Transaksi</h3>
                </div>
                @if($transactions->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada transaksi</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-slate-50/70">
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kendaraan</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Liter</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($transactions as $trx)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-slate-800">{{ $trx->kendaraan->no_polisi }}</span>
                                        <span class="text-xs text-slate-400 ml-1">({{ $trx->kendaraan->jenis_bbm }})</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 text-right">{{ $trx->liter }} L</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800 text-right">Rp {{ number_format($trx->total) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100">
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
            width: 50,
            height: 50,
            colorDark: '#1e293b',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        function downloadCard() {
            const card = document.getElementById('personel-card');
            
            // Generate QR Image first to ensure it's rendered
            // Wait a bit for images to load
            html2canvas(card, {
                scale: 3, // High resolution
                backgroundColor: null,
                useCORS: true,
                logging: false
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Kartu-Personel-{{ auth()->user()->personel->nrp }}.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.9);
                link.click();
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

