import os

def fix_xtext(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Replace the x-text bindings for month 1, 2, 3
    content = content.replace(
        '''<span class="text-gray-400" x-text="getHari(1, '{{ strtolower($k->kategori_kendaraan ?? 'operasional') }}')"></span>''',
        '''<span class="text-gray-400 span-hari-1">0</span>'''
    )
    content = content.replace(
        '''<span class="text-gray-400" x-text="getHari(2, '{{ strtolower($k->kategori_kendaraan ?? 'operasional') }}')"></span>''',
        '''<span class="text-gray-400 span-hari-2">0</span>'''
    )
    content = content.replace(
        '''<span class="text-gray-400" x-text="getHari(3, '{{ strtolower($k->kategori_kendaraan ?? 'operasional') }}')"></span>''',
        '''<span class="text-gray-400 span-hari-3">0</span>'''
    )
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_xtext('resources/views/admin/rendis/edit.blade.php')
fix_xtext('resources/views/admin/rendis/create.blade.php')
print("Fixed x-text")
