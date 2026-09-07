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

    <form id="form-edit-rendis" action="{{ route('admin.rendis.update', $rendisBbm->id) }}" method="POST" class="space-y-6">
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
                    <input type="hidden" name="pembelian_pertamax" id="hidden-pembelian-ptx" value="{{ $rendisBbm->pembelian_pertamax }}">
                    <input type="text" id="input-pembelian-ptx" value="{{ $rendisBbm->pembelian_pertamax }}" min="0" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Netto (Set. Susut): <span id="netto-ptx" class="font-bold text-gray-900 dark:text-white">0</span> L</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembelian P. Dex</label>
                    <input type="hidden" name="pembelian_pertamina_dex" id="hidden-pembelian-dex" value="{{ $rendisBbm->pembelian_pertamina_dex }}">
                    <input type="text" id="input-pembelian-dex" value="{{ $rendisBbm->pembelian_pertamina_dex }}" min="0" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Netto (Set. Susut): <span id="netto-dex" class="font-bold text-gray-900 dark:text-white">0</span> L</p>
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
                <table id="tabel-kendaraan" class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700 text-xs">
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
                        @php 
                            $rk = $existingRendisKendaraans->get($k->id); 
                            $currentUraian = strtolower(trim($rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional'));
                            $katKey = $currentUraian == 'opsnal' ? 'operasional' : $currentUraian;
                            $hk1 = $rendisBbm->{"bulan1_hari_{$katKey}"} ?? 22;
                            $hk2 = $rendisBbm->{"bulan2_hari_{$katKey}"} ?? 22;
                            $hk3 = $rendisBbm->{"bulan3_hari_{$katKey}"} ?? 22;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors kendaraan-row" data-satker-id="{{ $satkerId }}" data-kategori="{{ $katKey }}" data-jenis="{{ strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) }}">
                            <td class="px-3 py-1 text-center border border-gray-200 dark:border-gray-700">{{ $idx + 1 }}</td>
                            <td class="px-1 py-1 border border-gray-200 dark:border-gray-700">
                                <select name="kendaraan[{{ $k->id }}][uraian]" class="w-full text-xs p-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white input-uraian">
                                    <option value="Opsnal" {{ $currentUraian == 'opsnal' || $currentUraian == 'operasional' ? 'selected' : '' }}>Opsnal</option>
                                    <option value="Staff" {{ $currentUraian == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Pimpinan" {{ $currentUraian == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
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
                                    <span class="hari-display input-hari-1 w-10 p-0.5 text-center text-xs rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/30" data-val="{{ $hk1 }}" title="Klik untuk edit">{{ $hk1 }}</span>
                                    <input type="hidden" name="kendaraan[{{ $k->id }}][jumlah_hari]" class="input-hari-1-hidden" value="{{ $hk1 }}">
                                    <span class="text-gray-500 text-[10px]">hr</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b1-total inline-block min-w-[3rem] text-right" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan1_total ?? 0 }}</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan1_total]" value="{{ $rk->bulan1_total ?? 0 }}" class="input-b1-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b1-total-dex inline-block min-w-[3rem] text-right" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan1_total ?? 0 }}</span>
                            </td>

                            {{-- BULAN 2 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-0.5">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari_b2]" value="{{ $rk->liter_per_hari_b2 ?? ($rk->liter_per_hari ?? 0) }}" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-2">
                                    <span class="text-gray-500 text-[10px]">Ltr</span>
                                    <span class="text-gray-400">x</span>
                                    <span class="hari-display input-hari-2 w-10 p-0.5 text-center text-xs rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/30" data-val="{{ $hk2 }}" title="Klik untuk edit">{{ $hk2 }}</span>
                                    <input type="hidden" name="kendaraan[{{ $k->id }}][hari_b2]" class="input-hari-2-hidden" value="{{ $hk2 }}">
                                    <span class="text-gray-500 text-[10px]">hr</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b2-total inline-block min-w-[3rem] text-right" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan2_total ?? 0 }}</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan2_total]" value="{{ $rk->bulan2_total ?? 0 }}" class="input-b2-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b2-total-dex inline-block min-w-[3rem] text-right" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan2_total ?? 0 }}</span>
                            </td>

                            {{-- BULAN 3 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-0.5">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari_b3]" value="{{ $rk->liter_per_hari_b3 ?? ($rk->liter_per_hari ?? 0) }}" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-3">
                                    <span class="text-gray-500 text-[10px]">Ltr</span>
                                    <span class="text-gray-400">x</span>
                                    <span class="hari-display input-hari-3 w-10 p-0.5 text-center text-xs rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/30" data-val="{{ $hk3 }}" title="Klik untuk edit">{{ $hk3 }}</span>
                                    <input type="hidden" name="kendaraan[{{ $k->id }}][hari_b3]" class="input-hari-3-hidden" value="{{ $hk3 }}">
                                    <span class="text-gray-500 text-[10px]">hr</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b3-total inline-block min-w-[3rem] text-right" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan3_total ?? 0 }}</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan3_total]" value="{{ $rk->bulan3_total ?? 0 }}" class="input-b3-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b3-total-dex inline-block min-w-[3rem] text-right" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamina_dex')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>{{ $rk->bulan3_total ?? 0 }}</span>
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
    const satkerTotals = {};
    const satkerRows = {};
    const allCachedRows = [];

    // === HARI KERJA CONFIG ===
    function getHari(bulan, kategori) {
        const id = 'b' + bulan + '_' + (kategori === 'pimpinan' ? 'pi' : (kategori === 'staff' ? 'st' : 'op'));
        const el = document.getElementById(id);
        return el ? (parseInt(el.value) || 0) : 0;
    }

    function fmt(n){ return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

    // Ambil nilai hari dari span (yang pakai data-val)
    function getHariVal(spanEl, bulan, kat) {
        if (!spanEl) return getHari(bulan, kat);
        const v = parseInt(spanEl.dataset.val) || 0;
        return v > 0 ? v : getHari(bulan, kat);
    }

    // === ROW CALCULATION menggunakan cache ===
    function updateRowTotals(rc) {
        const lph1 = parseFloat(rc.inLph1.value) || 0;
        const lph2 = parseFloat(rc.inLph2.value) || 0;
        const lph3 = parseFloat(rc.inLph3.value) || 0;

        const hari1 = getHariVal(rc.spHari1, 1, rc.kat);
        const hari2 = getHariVal(rc.spHari2, 2, rc.kat);
        const hari3 = getHariVal(rc.spHari3, 3, rc.kat);

        // Update display span ONLY if changed (do NOT write to dataset.val here, as it locks in the default)
        if(rc.spHari1 && rc.spHari1.innerText != hari1){ rc.spHari1.innerText = hari1; }
        if(rc.spHari2 && rc.spHari2.innerText != hari2){ rc.spHari2.innerText = hari2; }
        if(rc.spHari3 && rc.spHari3.innerText != hari3){ rc.spHari3.innerText = hari3; }
        
        // Update hidden ONLY if changed
        if(rc.hidHari1 && rc.hidHari1.value != hari1) rc.hidHari1.value = hari1;
        if(rc.hidHari2 && rc.hidHari2.value != hari2) rc.hidHari2.value = hari2;
        if(rc.hidHari3 && rc.hidHari3.value != hari3) rc.hidHari3.value = hari3;

        const b1 = Math.round(lph1 * hari1), b2 = Math.round(lph2 * hari2), b3 = Math.round(lph3 * hari3);
        
        if(rc.inB1.value != b1) rc.inB1.value = b1;
        if(rc.inB2.value != b2) rc.inB2.value = b2;
        if(rc.inB3.value != b3) rc.inB3.value = b3;
        
        const fb1 = fmt(b1), fb2 = fmt(b2), fb3 = fmt(b3);
        if(rc.spB1 && rc.spB1.innerText !== fb1) rc.spB1.innerText = fb1;
        if(rc.spB2 && rc.spB2.innerText !== fb2) rc.spB2.innerText = fb2;
        if(rc.spB3 && rc.spB3.innerText !== fb3) rc.spB3.innerText = fb3;
    }

    function rebuildSatkerTotal(satkerId) {
        let p1=0,d1=0,p2=0,d2=0,p3=0,d3=0;
        var rows = satkerRows[satkerId] || [];
        for(var i=0;i<rows.length;i++){
            var rc=rows[i];
            var b1=parseInt(rc.inB1.value)||0, b2=parseInt(rc.inB2.value)||0, b3=parseInt(rc.inB3.value)||0;
            if(rc.j==='pertamax'){p1+=b1;p2+=b2;p3+=b3;}else{d1+=b1;d2+=b2;d3+=b3;}
        }
        satkerTotals[satkerId]={p1,d1,p2,d2,p3,d3};
        var st=document.querySelector('tr.satker-total[data-satker-id="'+satkerId+'"]');
        if(st){
            var set = function(cls, val) {
                var el = st.querySelector(cls);
                var fv = fmt(val);
                if(el && el.innerText !== fv) el.innerText = fv;
            };
            set('.st-p1', p1); set('.st-d1', d1); set('.st-p2', p2);
            set('.st-d2', d2); set('.st-p3', p3); set('.st-d3', d3);
        }
    }

    function updateGrandTotalDOM() {
        let tp1=0,td1=0,tp2=0,td2=0,tp3=0,td3=0;
        for(const id in satkerTotals){const s=satkerTotals[id];tp1+=s.p1;td1+=s.d1;tp2+=s.p2;td2+=s.d2;tp3+=s.p3;td3+=s.d3;}
        var $=function(id,v){var e=document.getElementById(id);var fv=fmt(v);if(e && e.innerText!==fv)e.innerText=fv;};
        $('grand-total-b1-ptx',tp1);$('grand-total-b1-dex',td1);$('grand-total-b2-ptx',tp2);$('grand-total-b2-dex',td2);$('grand-total-b3-ptx',tp3);$('grand-total-b3-dex',td3);
        var twPtx=tp1+tp2+tp3,twDex=td1+td2+td3;
        $('grand-total-triwulan-ptx',twPtx);$('grand-total-triwulan-dex',twDex);
        var pPtx=parseFloat(document.getElementById('hidden-pembelian-ptx').value)||0;
        var pDex=parseFloat(document.getElementById('hidden-pembelian-dex').value)||0;
        var susut=parseFloat(document.getElementById('input-susut').value)||0;
        var limPtx=Math.floor(pPtx-(pPtx*(susut/100))),limDex=Math.floor(pDex-(pDex*(susut/100)));
        $('maksimal-distribusi-ptx',limPtx);$('maksimal-distribusi-dex',limDex);
        $('netto-ptx',limPtx);$('netto-dex',limDex);
        var sPtx=document.getElementById('status-ptx');
        if(sPtx){
            var hPtx=twPtx>limPtx?'<span class="text-rose-600 font-bold">Melebihi batas (+'+fmt(twPtx-limPtx)+' L)</span>':'<span class="text-emerald-600 font-bold">Aman (Sisa '+fmt(limPtx-twPtx)+' L)</span>';
            if(sPtx.innerHTML!==hPtx) sPtx.innerHTML=hPtx;
        }
        var sDex=document.getElementById('status-dex');
        if(sDex){
            var hDex=twDex>limDex?'<span class="text-rose-600 font-bold">Melebihi batas (+'+fmt(twDex-limDex)+' L)</span>':'<span class="text-emerald-600 font-bold">Aman (Sisa '+fmt(limDex-twDex)+' L)</span>';
            if(sDex.innerHTML!==hDex) sDex.innerHTML=hDex;
        }
    }

    function rebuildAllTotals() {
        for(const id in satkerTotals) satkerTotals[id]={p1:0,d1:0,p2:0,d2:0,p3:0,d3:0};
        for(var i=0;i<allCachedRows.length;i++){
            var rc=allCachedRows[i],sId=rc.sId,j=rc.j;
            var b1=parseInt(rc.inB1.value)||0,b2=parseInt(rc.inB2.value)||0,b3=parseInt(rc.inB3.value)||0;
            if(!satkerTotals[sId])satkerTotals[sId]={p1:0,d1:0,p2:0,d2:0,p3:0,d3:0};
            if(j==='pertamax'){satkerTotals[sId].p1+=b1;satkerTotals[sId].p2+=b2;satkerTotals[sId].p3+=b3;}
            else{satkerTotals[sId].d1+=b1;satkerTotals[sId].d2+=b2;satkerTotals[sId].d3+=b3;}
        }
        for(const id in satkerTotals){
            const s=satkerTotals[id];
            var st=document.querySelector('tr.satker-total[data-satker-id="'+id+'"]');
            if(st){st.querySelector('.st-p1').innerText=fmt(s.p1);st.querySelector('.st-d1').innerText=fmt(s.d1);st.querySelector('.st-p2').innerText=fmt(s.p2);st.querySelector('.st-d2').innerText=fmt(s.d2);st.querySelector('.st-p3').innerText=fmt(s.p3);st.querySelector('.st-d3').innerText=fmt(s.d3);}
        }
        updateGrandTotalDOM();
    }

    function recalculateAll() {
        for(var i=0;i<allCachedRows.length;i++) updateRowTotals(allCachedRows[i]);
        rebuildAllTotals();
    }

    // === BUILD ROW CACHE ===
    document.querySelectorAll('tr.satker-total').forEach(function(st){
        satkerTotals[st.dataset.satkerId]={p1:0,d1:0,p2:0,d2:0,p3:0,d3:0};
        satkerRows[st.dataset.satkerId]=[];
    });

    document.querySelectorAll('tr.kendaraan-row').forEach(function(tr){
        var sId=tr.dataset.satkerId;
        var rc={
            tr:tr, sId:sId, j:tr.dataset.jenis, kat:tr.dataset.kategori,
            inLph1:tr.querySelector('.input-lph-1'),
            inLph2:tr.querySelector('.input-lph-2'),
            inLph3:tr.querySelector('.input-lph-3'),
            spHari1:tr.querySelector('.input-hari-1'),  // sekarang span
            spHari2:tr.querySelector('.input-hari-2'),
            spHari3:tr.querySelector('.input-hari-3'),
            hidHari1:tr.querySelector('.input-hari-1-hidden'),
            hidHari2:tr.querySelector('.input-hari-2-hidden'),
            hidHari3:tr.querySelector('.input-hari-3-hidden'),
            inB1:tr.querySelector('.input-b1-total'),
            inB2:tr.querySelector('.input-b2-total'),
            inB3:tr.querySelector('.input-b3-total'),
            spB1:tr.querySelector('.span-b1-total')||tr.querySelector('.span-b1-total-dex'),
            spB2:tr.querySelector('.span-b2-total')||tr.querySelector('.span-b2-total-dex'),
            spB3:tr.querySelector('.span-b3-total')||tr.querySelector('.span-b3-total-dex')
        };
        tr._rc=rc;
        allCachedRows.push(rc);
        if(satkerRows[sId]) satkerRows[sId].push(rc);
    });

    // === CLICK-TO-EDIT HARI (satu overlay input shared, bukan 800 input) ===
    var hariOverlay = document.createElement('input');
    hariOverlay.type='number'; hariOverlay.min='0';
    hariOverlay.style.cssText='position:absolute;z-index:9999;width:52px;text-align:center;font-size:12px;border:2px solid #3b82f6;border-radius:4px;padding:2px 4px;background:#fff;color:#111;box-shadow:0 2px 8px rgba(0,0,0,0.2);display:none;';
    document.body.appendChild(hariOverlay);
    var activeSpan=null, activeRc=null;

    function commitHari(){
        if(!activeSpan) return;
        var v=parseInt(hariOverlay.value)||0;
        activeSpan.dataset.val=v;
        activeSpan.innerText=v;
        // update hidden
        var hid=activeSpan.classList.contains('input-hari-1')?(activeRc&&activeRc.hidHari1):
                (activeSpan.classList.contains('input-hari-2')?(activeRc&&activeRc.hidHari2):(activeRc&&activeRc.hidHari3));
        if(hid) hid.value=v;
        hariOverlay.style.display='none';
        if(activeRc){
            updateRowTotals(activeRc);
            rebuildSatkerTotal(activeRc.sId);
            updateGrandTotalDOM();
        }
        activeSpan=null; activeRc=null;
    }

    hariOverlay.addEventListener('blur',function(){ setTimeout(commitHari,100); });
    hariOverlay.addEventListener('keydown',function(e){
        if(e.key==='Enter'){commitHari();}
        if(e.key==='Escape'){hariOverlay.style.display='none';activeSpan=null;activeRc=null;}
    });

    document.addEventListener('click',function(e){
        if(e.target.classList.contains('hari-display')){
            activeSpan=e.target;
            var tr=e.target.closest('tr.kendaraan-row');
            activeRc=tr?tr._rc:null;
            var rect=e.target.getBoundingClientRect();
            hariOverlay.style.display='block';
            hariOverlay.style.left=(rect.left+window.scrollX)+'px';
            hariOverlay.style.top=(rect.top+window.scrollY-1)+'px';
            hariOverlay.value=parseInt(e.target.dataset.val)||0;
            hariOverlay.select();
            hariOverlay.focus();
        } else if(e.target!==hariOverlay && hariOverlay.style.display!=='none'){
            commitHari();
        }
    });

    // === TABLE EVENT DELEGATION (lph & uraian saja) ===
    var tabel=document.getElementById('tabel-kendaraan');
    if(tabel){
        var calcTimeout;
        tabel.addEventListener('input',function(e){
            var t=e.target,tr=t.closest('tr.kendaraan-row');
            if(!tr) return;
            var rc=tr._rc; if(!rc) return;
            if(t.classList.contains('input-uraian')){ 
                rc.kat=t.value.toLowerCase(); 
                if(rc.spHari1) rc.spHari1.dataset.val = 0;
                if(rc.spHari2) rc.spHari2.dataset.val = 0;
                if(rc.spHari3) rc.spHari3.dataset.val = 0;
                updateRowTotals(rc);
                clearTimeout(calcTimeout);
                calcTimeout=setTimeout(function(){ rebuildSatkerTotal(rc.sId); updateGrandTotalDOM(); },200);
                return; 
            }
            if(t.classList.contains('input-lph-1')){ rc.inLph2.value=t.value; rc.inLph3.value=t.value; }
            updateRowTotals(rc);
            clearTimeout(calcTimeout);
            calcTimeout=setTimeout(function(){ rebuildSatkerTotal(rc.sId); updateGrandTotalDOM(); },200);
        });
        tabel.addEventListener('change',function(e){
            var t=e.target,tr=t.closest('tr.kendaraan-row');
            if(!tr) return;
            var rc=tr._rc; if(!rc) return;
            if(t.classList.contains('input-uraian')){ 
                rc.kat=t.value.toLowerCase(); 
                if(rc.spHari1) rc.spHari1.dataset.val = 0;
                if(rc.spHari2) rc.spHari2.dataset.val = 0;
                if(rc.spHari3) rc.spHari3.dataset.val = 0;
            }
            updateRowTotals(rc);
            clearTimeout(calcTimeout);
            calcTimeout=setTimeout(function(){ rebuildSatkerTotal(rc.sId); updateGrandTotalDOM(); },200);
        });
    }

    // === TERAPKAN HARI KERJA BUTTON ===
    var btnTerapkan=document.getElementById('btn-terapkan');
    if(btnTerapkan){ btnTerapkan.addEventListener('click',function(){ recalculateAll(); }); }

    // === PEMBELIAN / SUSUT LIVE UPDATE & FORMATTER ===
    ['input-pembelian-ptx','input-pembelian-dex'].forEach(function(id){
        var el=document.getElementById(id);
        if(el){
            el.addEventListener('input',function(e){
                var val=e.target.value.replace(/\D/g,'');
                var hiddenEl=document.getElementById('hidden'+id.substring(5));
                if(hiddenEl) hiddenEl.value=val||0;
                e.target.value=val?new Intl.NumberFormat('id-ID').format(parseInt(val)):'';
                updateGrandTotalDOM();
            });
            var val=el.value.replace(/\D/g,'');
            el.value=val?new Intl.NumberFormat('id-ID').format(parseInt(val)):'';
        }
    });
    var elSusut=document.getElementById('input-susut');
    if(elSusut) elSusut.addEventListener('input',function(){ updateGrandTotalDOM(); });

    // === INITIAL CALC (batched non-blocking) ===
    var i=0, batch=30;
    function processBatch(){
        var end=Math.min(i+batch,allCachedRows.length);
        for(;i<end;i++) updateRowTotals(allCachedRows[i]);
        if(i<allCachedRows.length){ requestAnimationFrame(processBatch); }
        else { rebuildAllTotals(); }
    }
    if(allCachedRows.length>0){ requestAnimationFrame(processBatch); }
    else { rebuildAllTotals(); }

    // === SUBMIT VIA AJAX TO BYPASS MAX_INPUT_VARS ===
    var formEdit=document.getElementById('form-edit-rendis');
    if(formEdit){
        formEdit.addEventListener('submit',async function(e){
            e.preventDefault();
            // commit any open hari overlay dulu
            if(activeSpan) commitHari();
            var btn=formEdit.querySelector('button[type="submit"]');
            var originalText=btn.innerText;
            btn.disabled=true; btn.innerText='Menyimpan...';

            var formData=new FormData(formEdit);
            var data={
                _token:formData.get('_token'),
                _method:formData.get('_method')||'POST',
                pembelian_pertamax:formData.get('pembelian_pertamax'),
                pembelian_pertamina_dex:formData.get('pembelian_pertamina_dex'),
                susut_persen:formData.get('susut_persen'),
                triwulan:formData.get('triwulan'),
                tahun:formData.get('tahun'),
                bulan1_hari_operasional:formData.get('bulan1_hari_operasional'),
                bulan1_hari_staff:formData.get('bulan1_hari_staff'),
                bulan1_hari_pimpinan:formData.get('bulan1_hari_pimpinan'),
                bulan2_hari_operasional:formData.get('bulan2_hari_operasional'),
                bulan2_hari_staff:formData.get('bulan2_hari_staff'),
                bulan2_hari_pimpinan:formData.get('bulan2_hari_pimpinan'),
                bulan3_hari_operasional:formData.get('bulan3_hari_operasional'),
                bulan3_hari_staff:formData.get('bulan3_hari_staff'),
                bulan3_hari_pimpinan:formData.get('bulan3_hari_pimpinan'),
                kendaraan:{}
            };

            // Ambil lph & total dari cached rows (lebih cepat dari iterasi FormData)
            for(var ci=0;ci<allCachedRows.length;ci++){
                var rc2=allCachedRows[ci];
                var kId=rc2.inB1.name.match(/\[(\d+)\]/)?rc2.inB1.name.match(/\[(\d+)\]/)[1]:null;
                // Fallback: ambil dari name attribute input-lph-1
                if(!kId && rc2.inLph1 && rc2.inLph1.name){
                    var m=rc2.inLph1.name.match(/kendaraan\[(\d+)\]/);
                    if(m) kId=m[1];
                }
                if(!kId) continue;
                var h1=parseInt(rc2.spHari1?rc2.spHari1.dataset.val:0)||0;
                var h2=parseInt(rc2.spHari2?rc2.spHari2.dataset.val:0)||0;
                var h3=parseInt(rc2.spHari3?rc2.spHari3.dataset.val:0)||0;
                data.kendaraan[kId]={
                    liter_per_hari:rc2.inLph1.value,
                    liter_per_hari_b2:rc2.inLph2.value,
                    liter_per_hari_b3:rc2.inLph3.value,
                    bulan1_total:rc2.inB1.value,
                    bulan2_total:rc2.inB2.value,
                    bulan3_total:rc2.inB3.value,
                    hari_b1:h1, hari_b2:h2, hari_b3:h3
                };
                // uraian: ambil dari select
                var sel=rc2.tr.querySelector('.input-uraian');
                if(sel) data.kendaraan[kId].uraian=sel.value;
            }

            try{
                const response=await fetch(formEdit.action,{
                    method:'POST',
                    headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':data._token},
                    body:JSON.stringify(data)
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if(contentType && contentType.includes('application/json')){
                    result = await response.json();
                } else {
                    const text = await response.text();
                    console.error('Server returned non-JSON:', text);
                    alert('Error Server: ' + text.substring(0, 100));
                    btn.disabled = false; btn.innerText = originalText;
                    return;
                }

                if(response.ok && result.redirect){ window.location.href=result.redirect; }
                else{ 
                    alert('Gagal menyimpan: ' + (result.message||'Data tidak valid. Cek console.')); 
                    if(result.errors) console.error('Validation errors:', result.errors);
                    btn.disabled=false; btn.innerText=originalText; 
                }
            }catch(err){ 
                alert('Terjadi kesalahan JavaScript: ' + err.message); 
                console.error(err); 
                btn.disabled=false; btn.innerText=originalText; 
            }
        });
    }
});
</script>
</x-app-layout>
