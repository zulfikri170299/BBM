@props(['current' => 15])

<div class="flex items-center gap-2 text-sm text-slate-600">
    <label for="per_page" class="font-medium whitespace-nowrap">Tampilkan:</label>
    <select name="per_page" id="per_page" onchange="this.form.submit()"
        class="block py-1.5 pl-3 pr-8 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer bg-white">
        @foreach([10, 15, 25, 50, 100] as $value)
            <option value="{{ $value }}" {{ $current == $value ? 'selected' : '' }}>
                {{ $value }} Baris
            </option>
        @endforeach
    </select>
</div>