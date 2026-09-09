const fs = require('fs');

let file = 'd:/PROJEK/BBM/BBM/resources/views/satker/dashboard.blade.php';
let content = fs.readFileSync(file, 'utf8');

// Fix Saldo Kendaraan outer card
content = content.replace(
    /class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-900 border border-emerald-400 shadow-xl p-3 sm:p-6 text-slate-800 dark:text-white shadow-lg shadow-emerald-500\/5 group hover:shadow-emerald-500\/20 transition-all duration-300 hover:-translate-y-1"/g,
    'class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-700 to-rose-900 border border-rose-500/20 shadow-xl p-3 sm:p-6 text-white shadow-lg shadow-rose-500/5 group hover:shadow-rose-500/20 transition-all duration-300 hover:-translate-y-1"'
);

// Fix Saldo Kendaraan inner icon box
content = content.replace(
    /<div class="p-2 sm:p-3 bg-white\/20 border border-white\/30 rounded-lg sm:rounded-xl">/g,
    '<div class="p-2 sm:p-3 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl text-white">'
);

// Fix Saldo Kendaraan text colors (Total Saldo)
content = content.replace(
    /<p class="text-2xl sm:text-4xl font-black text-slate-800 dark:text-white leading-none">\{\{ rtrim\(rtrim\(number_format\(\$totalSaldoKendaraan, 2, ',', '\.'\), '0'\), ','\) \}\} <span class="text-sm sm:text-lg font-medium opacity-80">L<\/span><\/p>\s*<p class="text-\[10px\] sm:text-sm text-slate-800 dark:text-white\/90 font-medium leading-tight">Saldo Kendaraan<\/p>/g,
    '<p class="text-2xl sm:text-4xl font-black text-white leading-none">{{ rtrim(rtrim(number_format($totalSaldoKendaraan, 2, \',\', \'.\'), \'0\'), \',\') }} <span class="text-sm sm:text-lg font-medium opacity-80 text-white">L</span></p>\n                        <p class="text-[10px] sm:text-sm text-white/90 font-medium leading-tight">Saldo Kendaraan</p>'
);

// Fix Saldo Kendaraan inner boxes loop
content = content.replace(
    /@forelse\(\$saldoKendaraanPerBbm as \$bbm => \$total\)\s*<div class="bg-white\/10 backdrop-blur-sm border border-slate-300 dark:border-white\/20 shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white\/20">\s*<p class="text-\[9px\] sm:text-\[10px\] font-bold text-slate-800 dark:text-white\/80 uppercase tracking-wider truncate mb-1" title="\{\{ \$bbm \}\}">\{\{ \$bbm \}\}<\/p>\s*<p class="text-sm sm:text-base font-black text-slate-800 dark:text-white">\{\{ rtrim\(rtrim\(number_format\(\$total, 2, ',', '\.'\), '0'\), ','\) \}\} <span class="text-\[10px\] font-bold opacity-80">L<\/span><\/p>\s*<\/div>/g,
    `@forelse($saldoKendaraanPerBbm as $bbm => $total)
                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                                <p class="text-[9px] sm:text-[10px] font-bold text-white/90 uppercase tracking-wider truncate mb-1" title="{{ $bbm }}">{{ $bbm }}</p>
                                <p class="text-sm sm:text-base font-black text-white">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} <span class="text-[10px] font-bold opacity-80 text-white">L</span></p>
                            </div>`
);

// Fix Saldo Kendaraan empty state
content = content.replace(
    /<div class="col-span-1 sm:col-span-2 bg-white\/10 backdrop-blur-sm border border-slate-300 dark:border-white\/20 shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white\/20">\s*<p class="text-\[9px\] sm:text-\[10px\] font-bold text-slate-800 dark:text-white\/80 uppercase tracking-wider mb-1">Status<\/p>\s*<p class="text-xs sm:text-sm font-black text-slate-800 dark:text-white\/90">Belum ada saldo<\/p>\s*<\/div>/g,
    `<div class="col-span-1 sm:col-span-2 bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                                <p class="text-[9px] sm:text-[10px] font-bold text-white/80 uppercase tracking-wider mb-1">Status</p>
                                <p class="text-xs sm:text-sm font-black text-white/90">Belum ada saldo</p>
                            </div>`
);


// Fix Hutang outer card
content = content.replace(
    /class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-800 border border-indigo-400 shadow-xl p-3 sm:p-6 text-slate-800 dark:text-white shadow-lg shadow-indigo-500\/5 group hover:shadow-indigo-500\/20 transition-all duration-300 hover:-translate-y-1"/g,
    'class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-slate-600 to-slate-800 dark:from-slate-800 dark:to-slate-900 border border-slate-500/20 shadow-xl p-3 sm:p-6 text-white shadow-lg shadow-slate-500/5 group hover:shadow-slate-500/20 transition-all duration-300 hover:-translate-y-1"'
);

// Fix Hutang text colors (Total Hutang)
content = content.replace(
    /<p class="text-2xl sm:text-4xl font-black text-slate-800 dark:text-white leading-none">\{\{ rtrim\(rtrim\(number_format\(\$totalHutang, 2, ',', '\.'\), '0'\), ','\) \}\} <span class="text-sm sm:text-lg font-medium opacity-80">L<\/span><\/p>\s*<p class="text-\[10px\] sm:text-sm text-slate-800 dark:text-white\/90 font-medium leading-tight">Total Hutang \(Bon\)<\/p>/g,
    '<p class="text-2xl sm:text-4xl font-black text-white leading-none">{{ rtrim(rtrim(number_format($totalHutang, 2, \',\', \'.\'), \'0\'), \',\') }} <span class="text-sm sm:text-lg font-medium opacity-80 text-white">L</span></p>\n                        <p class="text-[10px] sm:text-sm text-white/90 font-medium leading-tight">Total Hutang (Bon)</p>'
);

// Fix Hutang inner boxes loop to have dynamic colors (Orange for Pertamax, Blue for Dex)
content = content.replace(
    /@forelse\(\$hutangPerBbm as \$bbm => \$total\)\s*<div class="bg-white\/10 backdrop-blur-sm border border-slate-300 dark:border-white\/20 shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white\/20">\s*<p class="text-\[9px\] sm:text-\[10px\] font-bold text-slate-800 dark:text-white\/80 uppercase tracking-wider truncate mb-1" title="\{\{ \$bbm \}\}">\{\{ \$bbm \}\}<\/p>\s*<p class="text-sm sm:text-base font-black text-slate-800 dark:text-white">\{\{ rtrim\(rtrim\(number_format\(\$total, 2, ',', '\.'\), '0'\), ','\) \}\} <span class="text-\[10px\] font-bold opacity-80">L<\/span><\/p>\s*<\/div>/g,
    `@forelse($hutangPerBbm as $bbm => $total)
                            @php
                                if (stripos($bbm, 'pertamax') !== false) {
                                    $innerBg = 'bg-orange-500/80 hover:bg-orange-600/80 border-orange-400/50';
                                } elseif (stripos($bbm, 'dex') !== false) {
                                    $innerBg = 'bg-blue-500/80 hover:bg-blue-600/80 border-blue-400/50';
                                } else {
                                    $innerBg = 'bg-white/10 hover:bg-white/20 border-white/20';
                                }
                            @endphp
                            <div class="{{ $innerBg }} backdrop-blur-sm border shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors">
                                <p class="text-[9px] sm:text-[10px] font-bold text-white/90 uppercase tracking-wider truncate mb-1" title="{{ $bbm }}">{{ $bbm }}</p>
                                <p class="text-sm sm:text-base font-black text-white">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} <span class="text-[10px] font-bold opacity-80 text-white">L</span></p>
                            </div>`
);

// Fix Hutang empty state
content = content.replace(
    /<div class="col-span-1 sm:col-span-2 bg-white\/10 backdrop-blur-sm border border-slate-300 dark:border-white\/20 shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white\/20">\s*<p class="text-\[9px\] sm:text-\[10px\] font-bold text-slate-800 dark:text-white\/80 uppercase tracking-wider mb-1">Status<\/p>\s*<p class="text-xs sm:text-sm font-black text-slate-800 dark:text-white\/90">Belum ada hutang<\/p>\s*<\/div>/g,
    `<div class="col-span-1 sm:col-span-2 bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-2 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                                <p class="text-[9px] sm:text-[10px] font-bold text-white/80 uppercase tracking-wider mb-1">Status</p>
                                <p class="text-xs sm:text-sm font-black text-white/90">Belum ada hutang</p>
                            </div>`
);

fs.writeFileSync(file, content, 'utf8');
console.log("satker/dashboard.blade.php updated!");
