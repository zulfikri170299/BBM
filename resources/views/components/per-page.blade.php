@props(['current' => 15])

<div class="flex items-center gap-1.5 sm:gap-2 text-sm text-slate-600 dark:text-slate-400 shrink-0">
    <label for="per_page" class="hidden sm:inline font-medium whitespace-nowrap">Tampilkan:</label>
    @foreach(request()->query() as $key => $value)
        @if($key !== 'per_page' && $key !== 'page' && !is_array($value))
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @elseif(is_array($value))
            @foreach($value as $v)
                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
        @endif
    @endforeach
    <select name="per_page" id="per_page" onchange="this.form.submit()" data-no-custom-select
        class="block py-1.5 pl-2.5 pr-7 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-brand-primary transition-all cursor-pointer bg-white dark:bg-slate-900 shrink-0">
        @foreach([10, 15, 25, 50, 100] as $value)
            <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="{{ $value }}" {{ $current == $value ? 'selected' : '' }}>
                {{ $value }} Baris
            </option>
        @endforeach
    </select>
</div>
