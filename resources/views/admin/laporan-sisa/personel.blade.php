<x-app-layout>
  <div class="p-4 lg:p-8 space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $title }}</h1>
        <p class="mt-1 text-xs sm:text-sm font-medium text-slate-400">{{ $periode }}</p>
      </div>
      <div class="flex flex-row gap-2 w-full sm:w-auto">
        <a href="{{ route('admin.laporan-sisa.personel.print') }}" target="_blank"
          class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-rose-600 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all active:scale-95 gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          Cetak
        </a>
        <a href="{{ route('admin.dashboard') }}"
          class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-slate-800 text-slate-400 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95 gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali
        </a>
      </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-slate-800/50 border-b border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
            <tr>
              <th rowspan="2" class="px-4 py-4 border-r border-white/5 w-16">NO</th>
              <th rowspan="2" class="px-4 py-3 border-r border-white/5">SATUAN KERJA</th>
              <th colspan="2" class="px-3 sm:px-6 py-3 border-b border-white/5">SISA SALDO BBM (LITER)</th>
            </tr>
            <tr>
              <th class="px-4 py-3 border-r border-white/5">PERTAMAX</th>
              <th class="px-4 py-3">PERTAMINA DEX</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5">
            @foreach($rows as $index => $row)
              <tr class="hover:bg-slate-800/50 transition-all duration-200">
                <td class="px-4 py-4 text-center font-bold text-slate-400 border-r border-white/5">
                  {{ $index + 1 }}</td>
                <td class="px-4 py-3 font-black text-slate-300 border-r border-white/5 uppercase tracking-tight">
                  {{ $row['satker'] }}</td>
                <td class="px-4 py-3 text-center border-r border-white/5 ">
                  <span class="text-base font-black tracking-tighter {{ $row['pertamax'] < 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ number_format($row['pertamax'], 0, ',', '.') }}</span>
                </td>
                <td class="px-4 py-3 text-center ">
                  <span class="text-base font-black tracking-tighter {{ $row['dex'] < 0 ? 'text-red-600' : 'text-indigo-600' }}">{{ number_format($row['dex'], 0, ',', '.') }}</span>
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="bg-slate-900 border border-white/5 text-white font-black text-center text-lg border-t-2 border-white/5">
            <tr>
              <td colspan="2" class="px-6 py-5 text-right uppercase tracking-[0.2em] text-xs text-slate-400">TOTAL KESELURUHAN</td>
              <td class="px-6 py-5 border-l border-white/5 font-black tracking-tighter text-emerald-600">
                {{ rtrim(rtrim(number_format($totalPertamax, 2, ',', '.'), '0'), ',') }} <span class="text-[10px] text-emerald-600/50 ml-1">L</span>
              </td>
              <td class="px-6 py-5 border-l border-white/5 font-black tracking-tighter text-indigo-600">
                {{ rtrim(rtrim(number_format($totalDex, 2, ',', '.'), '0'), ',') }} <span class="text-[10px] text-indigo-600/50 ml-1">L</span>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Mobile Card View -->
    <div class="lg:hidden space-y-4">
      @foreach($rows as $index => $row)
        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm p-4 relative overflow-hidden group">
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-xl bg-slate-800 flex items-center justify-center text-xs font-black text-slate-400 shadow-sm border border-white/10">
                {{ $index + 1 }}
              </div>
              <h3 class="text-sm font-black text-slate-200 uppercase leading-tight tracking-tight max-w-[200px]">
                {{ $row['satker'] }}
              </h3>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="bg-slate-800/50 border-white/5 p-3 rounded-2xl border relative overflow-hidden">
              <p class="text-[9px] font-black {{ $row['pertamax'] < 0 ? 'text-red-600/60' : 'text-emerald-600/60' }} uppercase tracking-widest mb-1.5">Pertamax</p>
              <div class="flex items-baseline gap-1">
                <span class="text-lg font-black {{ $row['pertamax'] < 0 ? 'text-red-600' : 'text-emerald-600' }} tracking-tighter">{{ number_format($row['pertamax'], 0, ',', '.') }}</span>
                <span class="text-[9px] font-bold {{ $row['pertamax'] < 0 ? 'text-red-600/60' : 'text-emerald-600/60' }} uppercase">Liter</span>
              </div>
            </div>
            <div class="bg-slate-800/50 border-white/5 p-3 rounded-2xl border relative overflow-hidden">
              <p class="text-[9px] font-black {{ $row['dex'] < 0 ? 'text-red-600/60' : 'text-indigo-600/60' }} uppercase tracking-widest mb-1.5">P. Dex</p>
              <div class="flex items-baseline gap-1">
                <span class="text-lg font-black {{ $row['dex'] < 0 ? 'text-red-600' : 'text-indigo-600' }} tracking-tighter">{{ number_format($row['dex'], 0, ',', '.') }}</span>
                <span class="text-[9px] font-bold {{ $row['dex'] < 0 ? 'text-red-600/60' : 'text-indigo-600/60' }} uppercase">Liter</span>
              </div>
            </div>
          </div>
        </div>
      @endforeach

      <!-- Mobile Summary Card -->
      <div class="bg-slate-900 border border-white/5 rounded-3xl p-6 text-white border border-white/10 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
          <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 border-b border-white/5 pb-4">Total Keseluruhan</p>
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-xs font-bold text-slate-400">Pertamax</span>
              <div class="flex items-baseline gap-1">
                <span class="text-xl font-black text-emerald-600 tracking-tighter">{{ rtrim(rtrim(number_format($totalPertamax, 2, ',', '.'), '0'), ',') }}</span>
                <span class="text-[10px] font-bold text-emerald-600/40">L</span>
              </div>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-xs font-bold text-slate-400">Pertamina Dex</span>
              <div class="flex items-baseline gap-1">
                <span class="text-xl font-black text-indigo-600 tracking-tighter">{{ rtrim(rtrim(number_format($totalDex, 2, ',', '.'), '0'), ',') }}</span>
                <span class="text-[10px] font-bold text-indigo-600/40">L</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>