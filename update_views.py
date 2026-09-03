import re

def process_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Add handleTableInput to tbody
    content = content.replace('<tbody class="bg-white dark:bg-gray-800">', '<tbody class="bg-white dark:bg-gray-800" @input="handleTableInput" @change="handleTableInput">')

    # Replace input uraian with select
    uraian_input_regex = r'<input type="text" name="kendaraan\[\{\{ \$k->id \}\}\]\[uraian\]" value="([^"]+)" class="[^"]+" placeholder="Uraian">'
    
    def repl_uraian(match):
        val = match.group(1)
        return f'''@php $currentUraian = {val}; @endphp
                                <select name="kendaraan[{{ $k->id }}][uraian]" class="w-full text-xs p-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white input-uraian">
                                    <option value="Operasional" {{ $currentUraian == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                    <option value="Staff" {{ $currentUraian == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Pimpinan" {{ $currentUraian == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                </select>'''

    content = re.sub(uraian_input_regex, repl_uraian, content)

    # Remove @input="updateRow"
    content = content.replace('@input="updateRow" ', '')

    # Remove x-text="getHari(...)" and add span-hari classes
    # <span class="text-gray-400" x-text="getHari(1, '{{ strtolower($k->kategori_kendaraan ?? 'operasional') }}')"></span>
    
    content = re.sub(r'<span class="text-gray-400" x-text="getHari\(1,[^>]+></span>', r'<span class="text-gray-400 span-hari-1">0</span>', content)
    content = re.sub(r'<span class="text-gray-400" x-text="getHari\(2,[^>]+></span>', r'<span class="text-gray-400 span-hari-2">0</span>', content)
    content = re.sub(r'<span class="text-gray-400" x-text="getHari\(3,[^>]+></span>', r'<span class="text-gray-400 span-hari-3">0</span>', content)

    # Add handleTableInput to script
    script_to_add = '''
        handleTableInput(event) {
            const target = event.target;
            const tr = target.closest('tr');
            if (!tr) return;

            if (target.classList.contains('input-uraian')) {
                tr.dataset.kategori = target.value.toLowerCase();
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
            } else if (target.classList.contains('input-lph-1')) {
                const val = target.value;
                tr.querySelector('.input-lph-2').value = val;
                tr.querySelector('.input-lph-3').value = val;
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
            } else if (target.classList.contains('input-lph-2') || target.classList.contains('input-lph-3')) {
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
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
'''

    # Replace updateRowTotals and updateRow
    script_regex = r'updateRow\(event\) \{[\s\S]+?updateRowTotals\(tr\) \{[\s\S]+?\}\,'
    
    content = re.sub(script_regex, script_to_add.strip() + ',', content)

    # Initial calc needs to set hari spans too, which it does if recalculateAll calls updateRowTotals for each tr!
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

process_file('resources/views/admin/rendis/edit.blade.php')
process_file('resources/views/admin/rendis/create.blade.php')
print("Done")
