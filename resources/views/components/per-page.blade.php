@props(['current' => 15])

<div class="flex items-center gap-2 text-sm text-slate-400">
    <label for="per_page" class="font-medium whitespace-nowrap">Tampilkan:</label>
    <select name="per_page" id="per_page" onchange="this.form.submit()"
        class="block py-1.5 pl-3 pr-8 border border-white/10 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer bg-slate-900 border-white/10">
        @foreach([10, 15, 25, 50, 100] as $value)
            <option value="{{ $value }}" {{ $current == $value ? 'selected' : '' }}>
                {{ $value }} Baris
            </option>
        @endforeach
    </select>
</div>