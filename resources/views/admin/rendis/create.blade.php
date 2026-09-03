<x-app-layout>
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="rendisForm()">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Rendis BBM Baru</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Rencana pendistribusian BBM per Triwulan</p>
        </div>
        <a href="{{ route('admin.rendis.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">&larr; Kembali</a>
    </div>

    <form action="{{ route('admin.rendis.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- INFORMASI UMUM --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Umum</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Triwulan</label>
                    <select name="triwulan" x-model="triwulan" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                        <option value="TW I">TW I (Jan-Mar)</option>
                        <option value="TW II">TW II (Apr-Jun)</option>
                        <option value="TW III" selected>TW III (Jul-Sep)</option>
                        <option value="TW IV">TW IV (Okt-Des)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ date('Y') }}" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembelian Pertamax</label>
                    <input type="number" name="pembelian_pertamax" @input="recalculateGrandTotal()" min="0" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembelian P. Dex</label>
                    <input type="number" name="pembelian_pertamina_dex" @input="recalculateGrandTotal()" min="0" required class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Susut (%)</label>
                    <input type="number" name="susut_persen" @input="recalculateGrandTotal()" value="1.5" min="0" step="0.1" class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary">
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
                            <th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700" x-text="namaBulan[0]">Bulan 1</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700" x-text="namaBulan[1]">Bulan 2</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700" x-text="namaBulan[2]">Bulan 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">Operasional</td>
                            <td class="px-4 py-2"><input type="number" name="bulan1_hari_operasional" x-model.number="hari.b1_op" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan2_hari_operasional" x-model.number="hari.b2_op" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan3_hari_operasional" x-model.number="hari.b3_op" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">Staff</td>
                            <td class="px-4 py-2"><input type="number" name="bulan1_hari_staff" x-model.number="hari.b1_st" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan2_hari_staff" x-model.number="hari.b2_st" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan3_hari_staff" x-model.number="hari.b3_st" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">Pimpinan</td>
                            <td class="px-4 py-2"><input type="number" name="bulan1_hari_pimpinan" x-model.number="hari.b1_pi" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan2_hari_pimpinan" x-model.number="hari.b2_pi" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                            <td class="px-4 py-2"><input type="number" name="bulan3_hari_pimpinan" x-model.number="hari.b3_pi" min="0" class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-brand-primary focus:ring-brand-primary"></td>
                        </tr>
                    
                      </tbody>
                      
                  </table>
    
              </div>
              <div class="mt-4 flex justify-end">
                <button type="button" @click="recalculateAll()" class="px-5 py-2 bg-blue-600 dark:bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 shadow-sm transition-all text-sm flex items-center gap-2">
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
                            <th rowspan="3" class="px-3 py-2 text-center font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600 w-10">No</th>
                            <th rowspan="3" class="px-3 py-2 text-left font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600">Uraian</th>
                            <th rowspan="3" class="px-3 py-2 text-left font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600">Jenis Randis</th>
                            <th rowspan="3" class="px-3 py-2 text-center font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600">Nopol</th>
                            <th rowspan="3" class="px-3 py-2 text-center font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600">Jenis BBM</th>
                            <th colspan="3" class="px-3 py-2 text-center font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600" x-text="namaBulan[0]">Bulan 1</th>
                            <th colspan="3" class="px-3 py-2 text-center font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600" x-text="namaBulan[1]">Bulan 2</th>
                            <th colspan="3" class="px-3 py-2 text-center font-bold text-gray-600 dark:text-gray-300 uppercase border border-gray-300 dark:border-gray-600" x-text="namaBulan[2]">Bulan 3</th>
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-900/80">
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">Indeks</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">Pertamax</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">P. Dex</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">Indeks</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">Pertamax</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">P. Dex</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">Indeks</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">Pertamax</th>
                            <th class="px-2 py-1 text-center font-semibold text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">P. Dex</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @php $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX','XXI','XXII','XXIII','XXIV']; $satkerIdx = 0; @endphp
                        @foreach($kendaraansBySatker as $satkerId => $kendaraanList)
                        @php
                            $satker = $kendaraanList->first()->satker;
                            $satkerLabel = $romawi[$satkerIdx] ?? ($satkerIdx + 1);
                            $satkerIdx++;
                        @endphp
                        {{-- SEPARATOR SATKER --}}
                        <tr class="bg-yellow-50 dark:bg-yellow-900/20 border-t-2 border-yellow-400 dark:border-yellow-600">
                            <td class="px-3 py-2 text-center font-extrabold text-gray-800 dark:text-yellow-300 border border-gray-300 dark:border-gray-600">{{ $satkerLabel }}</td>
                            <td colspan="13" class="px-3 py-2 font-extrabold text-gray-800 dark:text-yellow-300 uppercase border border-gray-300 dark:border-gray-600">{{ $satker->nama_satker ?? 'TANPA SATKER' }}</td>
                        </tr>
                        @foreach($kendaraanList as $idx => $k)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors kendaraan-row" data-satker-id="{{ $satkerId }}" data-kategori="{{ strtolower($k->kategori_kendaraan ?? 'operasional') }}" data-jenis="{{ strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) }}">
                            <td class="px-3 py-1 text-center text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700">{{ $idx + 1 }}</td>
                            <td class="px-1 py-1 border border-gray-200 dark:border-gray-700">
                                @php $currentUraian = $k->kategori_kendaraan ?? 'Operasional'; @endphp
                                <select name="kendaraan[{ $k->id }][uraian]" class="w-full text-xs p-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white input-uraian">
                                    <option value="Opsnal" {{ $currentUraian == 'Opsnal' || $currentUraian == 'Operasional' ? 'selected' : '' }}>Opsnal</option>
                                    <option value="Staff" {{ $currentUraian == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Pimpinan" {{ $currentUraian == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                </select>
                            </td>
                            <td class="px-3 py-1 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 whitespace-nowrap">{{ $k->jenis_kendaraan ?? '-' }}</td>
                            <td class="px-3 py-1 text-center font-bold text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 whitespace-nowrap">{{ $k->no_polisi }}</td>
                            <td class="px-3 py-1 text-center font-bold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 whitespace-nowrap">
                                {{ $k->jenis_bbm ?: 'Pertamax' }}
                            </td>

                            {{-- BULAN 1 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-1">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari]" value="0" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-1">
                                    <span class="text-gray-400">x</span>
                                    <span class="text-gray-400 span-hari-1">0</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b1-total" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>0</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan1_total]" value="0" class="input-b1-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b1-total-dex" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>0</span>
                            </td>

                            {{-- BULAN 2 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-1">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari_b2]" value="0" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-2">
                                    <span class="text-gray-400">x</span>
                                    <span class="text-gray-400 span-hari-2">0</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b2-total" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>0</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan2_total]" value="0" class="input-b2-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b2-total-dex" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>0</span>
                            </td>

                            {{-- BULAN 3 --}}
                            <td class="px-1 py-1 text-center border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center gap-1">
                                    <input type="number" name="kendaraan[{{ $k->id }}][liter_per_hari_b3]" value="0" min="0" step="0.1" class="w-12 p-0.5 text-center text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm input-lph-3">
                                    <span class="text-gray-400">x</span>
                                    <span class="text-gray-400 span-hari-3">0</span>
                                </div>
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <span class="span-b3-total" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamax' ? 'style="display:none;"' : '' !!}>0</span>
                                <input type="hidden" name="kendaraan[{{ $k->id }}][bulan3_total]" value="0" class="input-b3-total">
                            </td>
                            <td class="px-2 py-1 text-center font-bold border border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="span-b3-total-dex" {!! strtolower(str_replace(' ', '_', $k->jenis_bbm ?? 'pertamax')) !== 'pertamina_dex' ? 'style="display:none;"' : '' !!}>0</span>
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
              <button type="submit" class="px-8 py-3 bg-brand-primary text-white font-bold rounded-lg hover:bg-brand-primary/90 focus:ring-4 focus:ring-brand-primary/30 transition-all shadow-md">
                Simpan Rendis
            </button>
        </div>
    </form>
</div>

<script>
function rendisForm() {
    const twMap = {
        'TW I':   ['Januari', 'Februari', 'Maret'],
        'TW II':  ['April', 'Mei', 'Juni'],
        'TW III': ['Juli', 'Agustus', 'September'],
        'TW IV':  ['Oktober', 'November', 'Desember'],
    };
    return {
        triwulan: 'TW III',
        hari: {
            b1_op: 23, b1_st: 23, b1_pi: 31,
            b2_op: 19, b2_st: 19, b2_pi: 31,
            b3_op: 22, b3_st: 22, b3_pi: 30,
        },
        get namaBulan() { return twMap[this.triwulan] || ['Bulan 1', 'Bulan 2', 'Bulan 3']; },
        getHari(bulan, kategori) {
            const h = this.hari;
            const k = kategori;
            if (bulan === 1) return k === 'pimpinan' ? h.b1_pi : (k === 'staff' ? h.b1_st : h.b1_op);
            if (bulan === 2) return k === 'pimpinan' ? h.b2_pi : (k === 'staff' ? h.b2_st : h.b2_op);
            return k === 'pimpinan' ? h.b3_pi : (k === 'staff' ? h.b3_st : h.b3_op);
        },
        init() {
            const self = this;
            const tabel = document.getElementById('tabel-kendaraan');
            if (tabel) {
                tabel.addEventListener('input', function(e) { self.handleTableInput(e); });
                tabel.addEventListener('change', function(e) { self.handleTableInput(e); });
            }
            requestAnimationFrame(() => {
                const rows = document.querySelectorAll('tr.kendaraan-row');
                let i = 0;
                const batchSize = 20;
                const processBatch = () => {
                    const end = Math.min(i + batchSize, rows.length);
                    for (; i < end; i++) {
                        self.updateRowTotals(rows[i]);
                    }
                    if (i < rows.length) {
                        requestAnimationFrame(processBatch);
                    } else {
                        self.recalculateAllSatkers();
                    }
                };
                processBatch();
            });
        },
        handleTableInput(event) {
            const target = event.target;
            const tr = target.closest('tr');
            if (!tr) return;

            if (target.classList.contains('input-uraian')) {
                tr.dataset.kategori = target.value.toLowerCase();
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
                this.recalculateGrandTotal();
            } else if (target.classList.contains('input-lph-1')) {
                const val = target.value;
                tr.querySelector('.input-lph-2').value = val;
                tr.querySelector('.input-lph-3').value = val;
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
                this.recalculateGrandTotal();
            } else if (target.classList.contains('input-lph-2') || target.classList.contains('input-lph-3')) {
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
                this.recalculateGrandTotal();
            }
        },
        updateRowTotals(tr) {
            const kategori = tr.dataset.kategori;
            const lph1 = parseFloat(tr.querySelector('.input-lph-1').value) || 0;
            const lph2 = parseFloat(tr.querySelector('.input-lph-2').value) || 0;
            const lph3 = parseFloat(tr.querySelector('.input-lph-3').value) || 0;
            
            const hari1 = this.getHari(1, kategori);
            const hari2 = this.getHari(2, kategori);
            const hari3 = this.getHari(3, kategori);

            if (tr.querySelector('.span-hari-1')) tr.querySelector('.span-hari-1').innerText = hari1;
            if (tr.querySelector('.span-hari-2')) tr.querySelector('.span-hari-2').innerText = hari2;
            if (tr.querySelector('.span-hari-3')) tr.querySelector('.span-hari-3').innerText = hari3;

            const b1Total = Math.round(lph1 * hari1);
            const b2Total = Math.round(lph2 * hari2);
            const b3Total = Math.round(lph3 * hari3);
            
            tr.querySelector('.input-b1-total').value = b1Total;
            tr.querySelector('.input-b2-total').value = b2Total;
            tr.querySelector('.input-b3-total').value = b3Total;
            
            if (tr.querySelector('.span-b1-total')) tr.querySelector('.span-b1-total').innerText = b1Total;
            if (tr.querySelector('.span-b2-total')) tr.querySelector('.span-b2-total').innerText = b2Total;
            if (tr.querySelector('.span-b3-total')) tr.querySelector('.span-b3-total').innerText = b3Total;
            
            if (tr.querySelector('.span-b1-total-dex')) tr.querySelector('.span-b1-total-dex').innerText = b1Total;
            if (tr.querySelector('.span-b2-total-dex')) tr.querySelector('.span-b2-total-dex').innerText = b2Total;
            if (tr.querySelector('.span-b3-total-dex')) tr.querySelector('.span-b3-total-dex').innerText = b3Total;
        },
        recalculateSatker(satkerId) {
            let p1=0, d1=0, p2=0, d2=0, p3=0, d3=0;
            const rows = document.querySelectorAll(`tr.kendaraan-row[data-satker-id="${satkerId}"]`);
            rows.forEach(tr => {
                const jenis = tr.dataset.jenis;
                const b1 = parseInt(tr.querySelector('.input-b1-total').value) || 0;
                const b2 = parseInt(tr.querySelector('.input-b2-total').value) || 0;
                const b3 = parseInt(tr.querySelector('.input-b3-total').value) || 0;
                
                if (jenis === 'pertamax') {
                    p1 += b1; p2 += b2; p3 += b3;
                } else {
                    d1 += b1; d2 += b2; d3 += b3;
                }
            });
            const satkerTr = document.querySelector(`tr.satker-total[data-satker-id="${satkerId}"]`);
            if (satkerTr) {
                satkerTr.querySelector('.st-p1').innerText = p1;
                satkerTr.querySelector('.st-d1').innerText = d1;
                satkerTr.querySelector('.st-p2').innerText = p2;
                satkerTr.querySelector('.st-d2').innerText = d2;
                satkerTr.querySelector('.st-p3').innerText = p3;
                satkerTr.querySelector('.st-d3').innerText = d3;
            }
        },
        
        recalculateGrandTotal() {
            let totalB1Ptx = 0, totalB1Dex = 0;
            let totalB2Ptx = 0, totalB2Dex = 0;
            let totalB3Ptx = 0, totalB3Dex = 0;

            document.querySelectorAll('tr.kendaraan-row').forEach(tr => {
                const jenis = tr.dataset.jenis;
                const b1 = parseInt(tr.querySelector('.input-b1-total').value) || 0;
                const b2 = parseInt(tr.querySelector('.input-b2-total').value) || 0;
                const b3 = parseInt(tr.querySelector('.input-b3-total').value) || 0;

                if (jenis === 'pertamax') {
                    totalB1Ptx += b1;
                    totalB2Ptx += b2;
                    totalB3Ptx += b3;
                } else {
                    totalB1Dex += b1;
                    totalB2Dex += b2;
                    totalB3Dex += b3;
                }
            });

            if(document.getElementById('grand-total-b1-ptx')) document.getElementById('grand-total-b1-ptx').innerText = totalB1Ptx;
            if(document.getElementById('grand-total-b1-dex')) document.getElementById('grand-total-b1-dex').innerText = totalB1Dex;
            if(document.getElementById('grand-total-b2-ptx')) document.getElementById('grand-total-b2-ptx').innerText = totalB2Ptx;
            if(document.getElementById('grand-total-b2-dex')) document.getElementById('grand-total-b2-dex').innerText = totalB2Dex;
            if(document.getElementById('grand-total-b3-ptx')) document.getElementById('grand-total-b3-ptx').innerText = totalB3Ptx;
            if(document.getElementById('grand-total-b3-dex')) document.getElementById('grand-total-b3-dex').innerText = totalB3Dex;

            const totalTriwulanPtx = totalB1Ptx + totalB2Ptx + totalB3Ptx;
            const totalTriwulanDex = totalB1Dex + totalB2Dex + totalB3Dex;
            
            if(document.getElementById('grand-total-triwulan-ptx')) document.getElementById('grand-total-triwulan-ptx').innerText = totalTriwulanPtx;
            if(document.getElementById('grand-total-triwulan-dex')) document.getElementById('grand-total-triwulan-dex').innerText = totalTriwulanDex;

            const pembelianPtx = parseFloat(document.querySelector('input[name="pembelian_pertamax"]').value) || 0;
            const pembelianDex = parseFloat(document.querySelector('input[name="pembelian_pertamina_dex"]').value) || 0;
            const susut = parseFloat(document.querySelector('input[name="susut_persen"]').value) || 0;

            const limitPtx = Math.floor(pembelianPtx - (pembelianPtx * (susut / 100)));
            const limitDex = Math.floor(pembelianDex - (pembelianDex * (susut / 100)));

            if(document.getElementById('maksimal-distribusi-ptx')) document.getElementById('maksimal-distribusi-ptx').innerText = limitPtx;
            if(document.getElementById('maksimal-distribusi-dex')) document.getElementById('maksimal-distribusi-dex').innerText = limitDex;

            const statusPtx = document.getElementById('status-ptx');
            if(statusPtx) {
                if(totalTriwulanPtx > limitPtx) {
                    statusPtx.innerHTML = `<span class="text-rose-600 font-bold">Melebihi batas (+${totalTriwulanPtx - limitPtx} L)</span>`;
                } else {
                    statusPtx.innerHTML = `<span class="text-emerald-600 font-bold">Aman (Sisa ${limitPtx - totalTriwulanPtx} L)</span>`;
                }
            }

            const statusDex = document.getElementById('status-dex');
            if(statusDex) {
                if(totalTriwulanDex > limitDex) {
                    statusDex.innerHTML = `<span class="text-rose-600 font-bold">Melebihi batas (+${totalTriwulanDex - limitDex} L)</span>`;
                } else {
                    statusDex.innerHTML = `<span class="text-emerald-600 font-bold">Aman (Sisa ${limitDex - totalTriwulanDex} L)</span>`;
                }
            }
        },
        recalculateAllSatkers() {

            const satkerIds = new Set();
            document.querySelectorAll('tr.kendaraan-row').forEach(tr => {
                if(tr.dataset.satkerId) satkerIds.add(tr.dataset.satkerId);
            });
            satkerIds.forEach(id => this.recalculateSatker(id));
            this.recalculateGrandTotal();
        },
        recalculateAll() {
            document.querySelectorAll('tr.kendaraan-row').forEach(tr => {
                this.updateRowTotals(tr);
            });
            this.recalculateAllSatkers();
        }
    }
}
</script>
</x-app-layout>
