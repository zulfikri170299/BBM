@props(['current' => 15])

<div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
    <label for="per_page" class="font-medium whitespace-nowrap">Tampilkan:</label>
    @foreach(request()->query() as $key => $value)
        @if($key !== 'per_page' && $key !== 'page' && !is_array($value))
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @elseif(is_array($value))
            @foreach($value as $v)
                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
        @endif
    @endforeach
    <select name="per_page" id="per_page" onchange="this.form.submit()"
        class="block py-1.5 pl-3 pr-8 border border-slate-200 dark:border-white/10 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer bg-white dark:bg-slate-900 border-slate-200 dark:border-white/10">
        @foreach([10, 15, 25, 50, 100] as $value)
            <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="{{ $value }}" {{ $current == $value ? 'selected' : '' }}>
                {{ $value }} Baris
            </option>
        @endforeach
    </select>
</div>