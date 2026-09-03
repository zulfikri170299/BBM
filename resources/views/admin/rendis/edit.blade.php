<x-app-layout>
@php
    $twMonths = [
        'TW I' => ['Januari','Februari','Maret'],
        'TW II' => ['April','Mei','Juni'],
        'TW III' => ['Juli','Agustus','September'],
        'TW IV' => ['Oktober','November','Desember'],
    ];
    $bulanNames = $twMonths[$rendisBbm->triwulan] ?? ['Bulan 1','Bulan 2','Bulan 3'];
@endphp
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Rendis BBM</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah data rencana pendistribusian BBM Triwulan</p>
        </div>
        <a href="{{ route('admin.rendis.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">&larr; Kembali</a>
    </div>

    <form action="{{ route('admin.rendis.update', $rendisBbm->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- INFORMASI UMUM --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Umum</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Triwulan</label>
                    <select name="triwulan" id="select-triwulan" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                        @foreach(['TW I','TW II','TW III','TW IV'] as $tw)
                        <option value="{{ $tw }}" {{ $rendisBbm->triwulan === $tw ? 'selected' : '' }}>{{ $tw }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ $rendisBbm->tahun }}" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembelian Pertamax</label>
                    <input type="number" name="pembelian_pertamax" id="input-pembelian-ptx" value="{{ $rendisBbm->pembelian_pertamax }}" min="0" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembelian P. Dex</label>
                    <input type="number" name="pembelian_pertamina_dex" id="input-pembelian-dex" value="{{ $rendisBbm->pembelian_pertamina_dex }}" min="0" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Susut (%)</label>
                    <input type="number" name="susut_persen" id="input-susut" value="{{ $rendisBbm->susut_persen }}" min="0" step="0.1" class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                </div>
            </div>
        </div>

        {{-- JUMLAH HARI PER BULAN --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Jumlah Hari Kerja per Bulan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-900/50">
                            <th class="px-4 py-3 text-left font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700">Kategori</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700">{{ $bulanNames[0] }}</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700">{{ $bulanNames[1] }}</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700">{{ $bulanNames[2] }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">Operasional</td>
                            <td class="px-4 py-2"><input type="number" name="bulan1_hari_operasional" id="b1_op" value="{{ $rendisBbm->bulan1_hari_operasional }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan2_hari_operasional" id="b2_op" value="{{ $rendisBbm->bulan2_hari_operasional }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan3_hari_operasional" id="b3_op" value="{{ $rendisBbm->bulan3_hari_operasional }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">Staff</td>
                            <td class="px-4 py-2"><input type="number" name="bulan1_hari_staff" id="b1_st" value="{{ $rendisBbm->bulan1_hari_staff }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan2_hari_staff" id="b2_st" value="{{ $rendisBbm->bulan2_hari_staff }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan3_hari_staff" id="b3_st" value="{{ $rendisBbm->bulan3_hari_staff }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">Pimpinan</td>
                            <td class="px-4 py-2"><input type="number" name="bulan1_hari_pimpinan" id="b1_pi" value="{{ $rendisBbm->bulan1_hari_pimpinan }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan2_hari_pimpinan" id="b2_pi" value="{{ $rendisBbm->bulan2_hari_pimpinan }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan3_hari_pimpinan" id="b3_pi" value="{{ $rendisBbm->bulan3_hari_pimpinan }}" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white hari-input"></td>
                        </tr>
                    
                      </tbody>
                      
                  </table>
    
              </div>
              <div class="mt-4 flex justify-end">
                <button type="button" id="btn-terapkan" class="px-5 py-2 bg-blue-600 dark:bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 shadow-sm transition-all text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    Terapkan Hari Kerja ke Tabel
                </button>
            </div>
        </div>

        {{-- TABEL KENDARAAN PER SATKER --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Kendaraan & Alokasi per Satker</h3>
            </div>
            <div class="overflow-x-auto">
                <table id="tabel-kendaraan" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                    <thead class="bg-gray-100 dark:bg-gray-900/80">
                        <tr>
                            <th rowspan="3" class="px-3 py-2 text-center font-bold uppercase border border-gray-300 dark:border-gray-600 w-10">No</th>
                            <th rowspan="3" class="px-3 py-2 text-left font-bold uppercase border border-gray-300 dark:border-gray-600">Uraian</th>
                            <th rowspan="3" class="px-3 py-2 text-left font-bold uppercase border border-gray-300 dark:border-gray-600">Jenis Randis</th>
                            <th rowspan="3" class="px-3 py-2 text-center font-bold uppercase border border-gray-300 dark:border-gray-600">Nopol</th>
                            <th rowspan="3" class="px-3 py-2 text-center font-bold uppercase border border-gray-300 dark:border-gray-600">Jenis BBM</th>
                            <th colspan="3" class="px-3 py-2 text-center font-bold uppercase border border-gray-300 dark:border-gray-600">{{ $bulanNames[0] }}</th>
                            <th colspan="3" class="px-3 py-2 text-center font-bold uppercase border border-gray-300 dark:border-gray-600">{{ $bulanNames[1] }}</th>
                            <th colspan="3" class="px-3 py-2 text-center font-bold uppercase border border-gray-300 dark:border-gray-600">{{ $bulanNames[2] }}</th>
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-900/80">
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">Indeks</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">Pertamax</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">P. Dex</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">Indeks</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">Pertamax</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">P. Dex</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">Indeks</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">Pertamax</th>
                            <th class="px-2 py-1 text-center font-semibold border border-gray-300 dark:border-gray-600">P. Dex</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @php $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX','XXI','XXII','XXIII','XXIV']; $satkerIdx = 0; @endphp
                        @foreach($kendaraansBySatker as $satkerId => $kendaraanList)
                        @php $satker = $kendaraanList->first()->satker; $satkerLabel = $romawi[$satkerIdx] ?? ($satkerIdx+1); $satkerIdx++; @endphp
                        <tr class="bg-yellow-50 dark:bg-yellow-900/20 border-t-2 border-yellow-400 dark:border-yellow-600">
                            <td class="px-3 py-2 text-center font-extrabold text-gray-800 dark:text-yellow-300 border border-gray-300 dark:border-gray-600">{{ $satkerLabel }}</td>
                            <td colspan="13" class="px-3 py-2 font-extrabold text-gray-800 dark:text-yellow-300 uppercase border border-gray-300 dark:border-gray-600">{{ $satker->nama_satker ?? 'TANPA SATKER' }}</td>
                        </tr>
                        @foreach($kendaraanList as $idx => $k)
                        @php $rk = $existingRendisKendaraans->get($k->id); @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors kendaraan-row" data-satker-id="{{ $satkerId }}" data-kategori="{{ strtolower($k->kategori_kendaraan ?? 'operasional') }}" data-jenis="{{ strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) }}">
                            <td class="px-3 py-1 text-center border border-gray-200 dark:border-gray-700">{{ $idx + 1 }}</td>
                            <td class="px-1 py-1 border border-gray-200 dark:border-gray-700">
                                @php $currentUraian = $rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional'; @endphp
                                <select name="kendaraan[{ $k->id }][uraian]" class="w-full text-xs p-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white input-uraian">
                                    <option value="Opsnal" {{ $currentUraian == 'Opsnal' || $currentUraian == 'Operasional' ? 'selected' : '' }}>Opsnal</option>
                                    <option value="Staff" {{ $currentUraian == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Pimpinan" {{ $currentUraian == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                </select>
                            </td>
                            <td class="px-3 py-1 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 whitespace-nowrap">{{ $k->jenis_kendaraan ?? '-' }}</td>
                            <td class="px-3 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 whitespace-nowrap">{{ $k->no_polisi }}</td>
                            <td class="px-3 py-1 text-center font-bold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 whitespace-nowrap">
                                {{ $k->jenis_bbm ?: 'Pertamax' }}
                            </td>
                            
                            {{-- BULAN 1 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-0.5">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari]" value="{{ $rk->liter_per_hari ?? 0 }}" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-1">
                                    <span class="text-gray-500 text-[10px]">Ltr</span>
                                    <span class="text-gray-400">x</span>
                                    <input type="number" value="0" min="0" class="w-10 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 shadow-sm input-hari-1">
                                    <span class="text-gray-500 text-[10px]">hr</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b1-total" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan1_total ?? 0 }}</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan1_total]" value="{{ $rk->bulan1_total ?? 0 }}" class="input-b1-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b1-total-dex" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan1_total ?? 0 }}</span>
                            </td>

                            {{-- BULAN 2 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-0.5">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari_b2]" value="{{ $rk->liter_per_hari_b2 ?? ($rk->liter_per_hari ?? 0) }}" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-2">
                                    <span class="text-gray-500 text-[10px]">Ltr</span>
                                    <span class="text-gray-400">x</span>
                                    <input type="number" value="0" min="0" class="w-10 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 shadow-sm input-hari-2">
                                    <span class="text-gray-500 text-[10px]">hr</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b2-total" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan2_total ?? 0 }}</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan2_total]" value="{{ $rk->bulan2_total ?? 0 }}" class="input-b2-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b2-total-dex" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan2_total ?? 0 }}</span>
                            </td>

                            {{-- BULAN 3 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-0.5">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari_b3]" value="{{ $rk->liter_per_hari_b3 ?? ($rk->liter_per_hari ?? 0) }}" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-3">
                                    <span class="text-gray-500 text-[10px]">Ltr</span>
                                    <span class="text-gray-400">x</span>
                                    <input type="number" value="0" min="0" class="w-10 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 shadow-sm input-hari-3">
                                    <span class="text-gray-500 text-[10px]">hr</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b3-total" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan3_total ?? 0 }}</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan3_total]" value="{{ $rk->bulan3_total ?? 0 }}" class="input-b3-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b3-total-dex" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamina_dex')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan3_total ?? 0 }}</span>
                            </td>
                        </tr>
                        @endforeach
                        {{-- SATKER JUMLAH --}}
                        <tr class="bg-gray-100 dark:bg-gray-700/50 satker-total" data-satker-id="{{ $satkerId }}">
                            <td colspan="5" class="px-3 py-2 text-right font-bold text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600">JUMLAH</td>
                            <td class="border border-gray-300 dark:border-gray-600"></td>
                            <td class="px-2 py-2 text-center font-bold text-blue-600 dark:text-blue-400 border border-gray-300 dark:border-gray-600 st-p1">0</td>
                            <td class="px-2 py-2 text-center font-bold text-emerald-600 dark:text-emerald-400 border border-gray-300 dark:border-gray-600 st-d1">0</td>
                            <td class="border border-gray-300 dark:border-gray-600"></td>
                            <td class="px-2 py-2 text-center font-bold text-blue-600 dark:text-blue-400 border border-gray-300 dark:border-gray-600 st-p2">0</td>
                            <td class="px-2 py-2 text-center font-bold text-emerald-600 dark:text-emerald-400 border border-gray-300 dark:border-gray-600 st-d2">0</td>
                            <td class="border border-gray-300 dark:border-gray-600"></td>
                            <td class="px-2 py-2 text-center font-bold text-blue-600 dark:text-blue-400 border border-gray-300 dark:border-gray-600 st-p3">0</td>
                            <td class="px-2 py-2 text-center font-bold text-emerald-600 dark:text-emerald-400 border border-gray-300 dark:border-gray-600 st-d3">0</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        
          <!-- RINGKASAN DISTRIBUSI -->
          <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
              <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ringkasan Distribusi Triwulan</h3>
              </div>
              <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Pertamax -->
                  <div class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-lg border border-blue-100 dark:border-blue-800">
                      <h4 class="text-blue-800 dark:text-blue-300 font-bold text-lg mb-4 flex items-center">
                          <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Pertamax
                      </h4>
                      <div class="space-y-3">
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B1:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b1-ptx">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B2:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b2-ptx">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm border-b border-blue-200 dark:border-blue-800 pb-3">
                              <span class="text-gray-600 dark:text-gray-400">Total B3:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b3-ptx">0</span>
                          </div>
                          <div class="flex justify-between items-center pt-1">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Total Distribusi:</span>
                              <span class="font-extrabold text-blue-700 dark:text-blue-400 text-lg"><span id="grand-total-triwulan-ptx">0</span> L</span>
                          </div>
                          <div class="flex justify-between items-center">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Maksimal (Batas):</span>
                              <span class="font-bold text-gray-900 dark:text-white"><span id="maksimal-distribusi-ptx">0</span> L</span>
                          </div>
                          <div class="mt-2 pt-3 border-t border-blue-200 dark:border-blue-800 text-right" id="status-ptx">
                              -
                          </div>
                      </div>
                  </div>
                  
                  <!-- Pertamina Dex -->
                  <div class="bg-emerald-50 dark:bg-emerald-900/20 p-5 rounded-lg border border-emerald-100 dark:border-emerald-800">
                      <h4 class="text-emerald-800 dark:text-emerald-300 font-bold text-lg mb-4 flex items-center">
                          <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span> Pertamina Dex
                      </h4>
                      <div class="space-y-3">
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B1:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b1-dex">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B2:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b2-dex">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm border-b border-emerald-200 dark:border-emerald-800 pb-3">
                              <span class="text-gray-600 dark:text-gray-400">Total B3:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b3-dex">0</span>
                          </div>
                          <div class="flex justify-between items-center pt-1">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Total Distribusi:</span>
                              <span class="font-extrabold text-emerald-700 dark:text-emerald-400 text-lg"><span id="grand-total-triwulan-dex">0</span> L</span>
                          </div>
                          <div class="flex justify-between items-center">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Maksimal (Batas):</span>
                              <span class="font-bold text-gray-900 dark:text-white"><span id="maksimal-distribusi-dex">0</span> L</span>
                          </div>
                          <div class="mt-2 pt-3 border-t border-emerald-200 dark:border-emerald-800 text-right" id="status-dex">
                              -
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        
          <div class="flex justify-end">
              <button type="submit" class="px-8 py-3 bg-brand-primary text-white font-bold rounded-lg hover:bg-brand-primary/90 focus:ring-4 focus:ring-brand-primary/30 transition-all shadow-md">Update Rendis</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === HARI KERJA CONFIG ===
    function getHari(bulan, kategori) {
        const id = 'b' + bulan + '_' + (kategori === 'pimpinan' ? 'pi' : (kategori === 'staff' ? 'st' : 'op'));
        const el = document.getElementById(id);
        return el ? (parseInt(el.value) || 0) : 0;
    }

    // === NUMBER FORMATTER ===
    function fmt(n){ return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

    // === ROW CALCULATION ===
    function updateRowTotals(tr) {
        const kategori = tr.dataset.kategori;
        const lph1 = parseFloat(tr.querySelector('.input-lph-1').value) || 0;
        const lph2 = parseFloat(tr.querySelector('.input-lph-2').value) || 0;
        const lph3 = parseFloat(tr.querySelector('.input-lph-3').value) || 0;

        // Read hari from row input (manual), fallback to global hari kerja
        const ih1 = tr.querySelector('.input-hari-1');
        const ih2 = tr.querySelector('.input-hari-2');
        const ih3 = tr.querySelector('.input-hari-3');
        const hari1 = (ih1 && ih1.value !== '' && parseInt(ih1.value) > 0) ? parseInt(ih1.value) : getHari(1, kategori);
        const hari2 = (ih2 && ih2.value !== '' && parseInt(ih2.value) > 0) ? parseInt(ih2.value) : getHari(2, kategori);
        const hari3 = (ih3 && ih3.value !== '' && parseInt(ih3.value) > 0) ? parseInt(ih3.value) : getHari(3, kategori);

        // Update hari input display
        if(ih1) ih1.value = hari1;
        if(ih2) ih2.value = hari2;
        if(ih3) ih3.value = hari3;

        const b1 = Math.round(lph1 * hari1), b2 = Math.round(lph2 * hari2), b3 = Math.round(lph3 * hari3);
        tr.querySelector('.input-b1-total').value = b1;
        tr.querySelector('.input-b2-total').value = b2;
        tr.querySelector('.input-b3-total').value = b3;

        const s = (cls, val) => { const e = tr.querySelector(cls); if(e) e.innerText = fmt(val); };
        s('.span-b1-total', b1); s('.span-b2-total', b2); s('.span-b3-total', b3);
        s('.span-b1-total-dex', b1); s('.span-b2-total-dex', b2); s('.span-b3-total-dex', b3);
    }

    // === SATKER SUBTOTAL ===
    function recalculateSatker(satkerId) {
        let p1=0,d1=0,p2=0,d2=0,p3=0,d3=0;
        document.querySelectorAll('tr.kendaraan-row[data-satker-id="'+satkerId+'"]').forEach(function(tr) {
            const j = tr.dataset.jenis;
            const b1 = parseInt(tr.querySelector('.input-b1-total').value)||0;
            const b2 = parseInt(tr.querySelector('.input-b2-total').value)||0;
            const b3 = parseInt(tr.querySelector('.input-b3-total').value)||0;
            if(j==='pertamax'){p1+=b1;p2+=b2;p3+=b3;}else{d1+=b1;d2+=b2;d3+=b3;}
        });
        var st = document.querySelector('tr.satker-total[data-satker-id="'+satkerId+'"]');
        if(st){st.querySelector('.st-p1').innerText=fmt(p1);st.querySelector('.st-d1').innerText=fmt(d1);st.querySelector('.st-p2').innerText=fmt(p2);st.querySelector('.st-d2').innerText=fmt(d2);st.querySelector('.st-p3').innerText=fmt(p3);st.querySelector('.st-d3').innerText=fmt(d3);}
    }

    // === GRAND TOTAL ===
    function recalculateGrandTotal() {
        let tp1=0,td1=0,tp2=0,td2=0,tp3=0,td3=0;
        document.querySelectorAll('tr.kendaraan-row').forEach(function(tr) {
            var j=tr.dataset.jenis,b1=parseInt(tr.querySelector('.input-b1-total').value)||0,b2=parseInt(tr.querySelector('.input-b2-total').value)||0,b3=parseInt(tr.querySelector('.input-b3-total').value)||0;
            if(j==='pertamax'){tp1+=b1;tp2+=b2;tp3+=b3;}else{td1+=b1;td2+=b2;td3+=b3;}
        });
        var $ = function(id,v){ var e=document.getElementById(id); if(e) e.innerText=fmt(v); };
        $('grand-total-b1-ptx',tp1);$('grand-total-b1-dex',td1);$('grand-total-b2-ptx',tp2);$('grand-total-b2-dex',td2);$('grand-total-b3-ptx',tp3);$('grand-total-b3-dex',td3);
        var twPtx=tp1+tp2+tp3, twDex=td1+td2+td3;
        $('grand-total-triwulan-ptx',twPtx);$('grand-total-triwulan-dex',twDex);
        var pPtx=parseFloat(document.getElementById('input-pembelian-ptx').value)||0;
        var pDex=parseFloat(document.getElementById('input-pembelian-dex').value)||0;
        var susut=parseFloat(document.getElementById('input-susut').value)||0;
        var limPtx=Math.floor(pPtx-(pPtx*(susut/100))), limDex=Math.floor(pDex-(pDex*(susut/100)));
        $('maksimal-distribusi-ptx',limPtx);$('maksimal-distribusi-dex',limDex);
        var sPtx=document.getElementById('status-ptx');
        if(sPtx){sPtx.innerHTML=twPtx>limPtx?'<span class="text-rose-600 font-bold">Melebihi batas (+'+fmt(twPtx-limPtx)+' L)</span>':'<span class="text-emerald-600 font-bold">Aman (Sisa '+fmt(limPtx-twPtx)+' L)</span>';}
        var sDex=document.getElementById('status-dex');
        if(sDex){sDex.innerHTML=twDex>limDex?'<span class="text-rose-600 font-bold">Melebihi batas (+'+fmt(twDex-limDex)+' L)</span>':'<span class="text-emerald-600 font-bold">Aman (Sisa '+fmt(limDex-twDex)+' L)</span>';}
    }

    function recalculateAllSatkers() {
        var ids = new Set();
        document.querySelectorAll('tr.kendaraan-row').forEach(function(tr){ if(tr.dataset.satkerId) ids.add(tr.dataset.satkerId); });
        ids.forEach(function(id){ recalculateSatker(id); });
        recalculateGrandTotal();
    }

    function recalculateAll() {
        document.querySelectorAll('tr.kendaraan-row').forEach(function(tr){ updateRowTotals(tr); });
        recalculateAllSatkers();
    }

    // === TABLE EVENT DELEGATION (single listener, very fast) ===
    var tabel = document.getElementById('tabel-kendaraan');
    if(tabel) {
        tabel.addEventListener('input', function(e) {
            var t=e.target, tr=t.closest('tr.kendaraan-row');
            if(!tr) return;
            if(t.classList.contains('input-uraian')){tr.dataset.kategori=t.value.toLowerCase();}
            else if(t.classList.contains('input-lph-1')){tr.querySelector('.input-lph-2').value=t.value;tr.querySelector('.input-lph-3').value=t.value;}
            // For hari inputs, don't reset to global value
            if(t.classList.contains('input-hari-1')||t.classList.contains('input-hari-2')||t.classList.contains('input-hari-3')){
                // Manual hari override - recalculate with custom value
                var lph1=parseFloat(tr.querySelector('.input-lph-1').value)||0;
                var lph2=parseFloat(tr.querySelector('.input-lph-2').value)||0;
                var lph3=parseFloat(tr.querySelector('.input-lph-3').value)||0;
                var h1=parseInt(tr.querySelector('.input-hari-1').value)||0;
                var h2=parseInt(tr.querySelector('.input-hari-2').value)||0;
                var h3=parseInt(tr.querySelector('.input-hari-3').value)||0;
                var b1=Math.round(lph1*h1),b2=Math.round(lph2*h2),b3=Math.round(lph3*h3);
                tr.querySelector('.input-b1-total').value=b1;tr.querySelector('.input-b2-total').value=b2;tr.querySelector('.input-b3-total').value=b3;
                var s=function(cls,val){var e2=tr.querySelector(cls);if(e2)e2.innerText=fmt(val);};
                s('.span-b1-total',b1);s('.span-b2-total',b2);s('.span-b3-total',b3);
                s('.span-b1-total-dex',b1);s('.span-b2-total-dex',b2);s('.span-b3-total-dex',b3);
                recalculateSatker(tr.dataset.satkerId); recalculateGrandTotal();
                return;
            }
            updateRowTotals(tr); recalculateSatker(tr.dataset.satkerId); recalculateGrandTotal();
        });
        tabel.addEventListener('change', function(e) {
            var t=e.target, tr=t.closest('tr.kendaraan-row');
            if(!tr) return;
            if(t.classList.contains('input-uraian')){tr.dataset.kategori=t.value.toLowerCase();}
            updateRowTotals(tr); recalculateSatker(tr.dataset.satkerId); recalculateGrandTotal();
        });
    }

    // === TERAPKAN HARI KERJA BUTTON ===
    var btnTerapkan = document.getElementById('btn-terapkan');
    if(btnTerapkan) { btnTerapkan.addEventListener('click', function(){ recalculateAll(); }); }

    // === PEMBELIAN / SUSUT LIVE UPDATE ===
    ['input-pembelian-ptx','input-pembelian-dex','input-susut'].forEach(function(id){
        var el = document.getElementById(id);
        if(el) el.addEventListener('input', function(){ recalculateGrandTotal(); });
    });

    // === INITIAL CALC (batched, non-blocking) ===
    var rows = document.querySelectorAll('tr.kendaraan-row');
    var i = 0, batch = 30;
    function processBatch() {
        var end = Math.min(i + batch, rows.length);
        for(; i < end; i++) { updateRowTotals(rows[i]); }
        if(i < rows.length) { requestAnimationFrame(processBatch); }
        else { recalculateAllSatkers(); }
    }
    if(rows.length > 0) { requestAnimationFrame(processBatch); }
});
</script>
</x-app-layout>
