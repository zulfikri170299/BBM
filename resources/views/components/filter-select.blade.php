@props([
    'name',
    'value' => '',
    'placeholder' => 'Semua',
    'options' => [],
    'searchable' => true,
    'searchPlaceholder' => 'Cari...',
    'icon' => 'building', // building, bbm, car, shield, calendar, filter
    'autoSubmit' => false,
])

@php
    // Normalisasi options ke format [['value' => ..., 'label' => ...]]
    $normalizedOptions = [];
    foreach ($options as $k => $v) {
        if (is_array($v)) {
            $normalizedOptions[] = [
                'value' => (string)($v['value'] ?? $v['id'] ?? $k),
                'label' => (string)($v['label'] ?? $v['nama'] ?? $v['name'] ?? $v['nama_satker'] ?? $v['text'] ?? $v['value'] ?? ''),
            ];
        } elseif (is_object($v)) {
            $normalizedOptions[] = [
                'value' => (string)($v->id ?? $v->value ?? $k),
                'label' => (string)($v->nama_satker ?? $v->nama ?? $v->name ?? $v->label ?? $v->no_polisi ?? $v->id ?? ''),
            ];
        } else {
            // Assoc or sequential array
            $optVal = is_numeric($k) ? $v : $k;
            $normalizedOptions[] = [
                'value' => (string)$optVal,
                'label' => (string)$v,
            ];
        }
    }

    $currentVal = (string)$value;
    $selectedLabel = $placeholder;
    foreach ($normalizedOptions as $opt) {
        if ((string)$opt['value'] === $currentVal && $currentVal !== '') {
            $selectedLabel = $opt['label'];
            break;
        }
    }
@endphp

<div class="relative w-full" :class="{ 'z-50': open }" x-data="{
    open: false,
    search: '',
    selectedVal: '{{ $currentVal }}',
    selectedLabel: '{{ addslashes($selectedLabel) }}',
    options: {{ json_encode($normalizedOptions) }},
    get filteredOptions() {
        if (!this.search) return this.options;
        const q = this.search.toLowerCase().trim();
        return this.options.filter(o => o.label.toLowerCase().includes(q));
    },
    selectOption(val, label) {
        this.selectedVal = val;
        this.selectedLabel = label;
        this.open = false;
        this.search = '';
        $nextTick(() => {
            const form = $el.closest('form');
            if (form && {{ $autoSubmit ? 'true' : 'false' }}) {
                form.submit();
            }
        });
    }
}" @click.outside="open = false">
    <!-- Hidden input to submit with the form -->
    <input type="hidden" name="{{ $name }}" :value="selectedVal">

    <!-- Trigger Button -->
    <button type="button" @click="open = !open; $nextTick(() => { if(open && $refs.searchInput) $refs.searchInput.focus() })"
        class="w-full h-11 px-3 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-left flex items-center justify-between transition-all hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
        :class="{ 'ring-2 ring-blue-500 border-transparent': open, 'border-blue-500/50 text-blue-600 dark:text-blue-400': selectedVal !== '' }">
        <div class="flex items-center gap-2 truncate pr-2">
            @if($icon === 'building')
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            @elseif($icon === 'bbm')
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            @elseif($icon === 'car')
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            @elseif($icon === 'shield')
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            @elseif($icon === 'calendar')
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            @else
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            @endif
            <span class="truncate font-semibold" :class="selectedVal ? 'text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400'" x-text="selectedLabel"></span>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            <template x-if="selectedVal !== ''">
                <span @click.stop="selectOption('', '{{ addslashes($placeholder) }}')" class="p-0.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-rose-500 transition-colors" title="Reset">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </span>
            </template>
            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl z-[60] overflow-hidden flex flex-col max-h-80">
        
        @if($searchable)
        <!-- Search input inside dropdown -->
        <div class="p-2.5 border-b border-slate-200 dark:border-white/10 bg-slate-50/70 dark:bg-slate-800/40 sticky top-0 z-10 shrink-0">
            <div class="relative">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-ref="searchInput" x-model="search" placeholder="{{ $searchPlaceholder }}"
                    class="w-full pl-8 pr-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>
        @endif

        <!-- Options List -->
        <div class="overflow-y-auto divide-y divide-slate-100 dark:divide-white/5 custom-scrollbar p-1 flex-1 min-h-0 overscroll-contain">
            <button type="button" @click="selectOption('', '{{ addslashes($placeholder) }}')"
                class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                :class="selectedVal === '' ? 'bg-blue-600/10 text-blue-600 dark:bg-blue-500/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                <span class="flex items-center gap-2 truncate pr-2">
                    <span class="w-2 h-2 rounded-full" :class="selectedVal === '' ? 'bg-blue-500' : 'bg-slate-300 dark:bg-slate-600'"></span>
                    {{ $placeholder }}
                </span>
                <template x-if="selectedVal === ''">
                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </template>
            </button>

            <template x-for="opt in filteredOptions" :key="opt.value">
                <button type="button" @click="selectOption(opt.value, opt.label)"
                    class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                    :class="selectedVal == opt.value ? 'bg-blue-600/10 text-blue-600 dark:bg-blue-500/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                    <span class="truncate pr-2" x-text="opt.label"></span>
                    <template x-if="selectedVal == opt.value">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </template>
                </button>
            </template>

            <template x-if="filteredOptions.length === 0">
                <div class="px-3 py-4 text-center text-xs text-slate-400">
                    Tidak ditemukan "<span x-text="search" class="font-bold"></span>"
                </div>
            </template>
        </div>
    </div>
</div>
