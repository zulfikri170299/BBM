import re

def process_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Add padding to inputs in Informasi Umum
    content = content.replace(
        'class="w-full rounded-lg border-gray-300',
        'class="w-full px-3 py-2 rounded-lg border-gray-300 shadow-sm'
    )

    # 2. Add padding to inputs in Hari Kerja table
    content = content.replace(
        'class="w-full text-center rounded border-gray-300',
        'class="w-full text-center px-3 py-2 rounded border-gray-300 shadow-sm'
    )

    # 3. Add borders to Hari Kerja table headers
    content = content.replace(
        '<th class="px-4 py-2 text-left font-bold text-gray-600 dark:text-gray-300">Kategori</th>',
        '<th class="px-4 py-3 text-left font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700">Kategori</th>'
    )
    content = content.replace(
        '<th class="px-4 py-2 text-center font-bold text-gray-600 dark:text-gray-300" x-text="namaBulan[0]">Bulan 1</th>',
        '<th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700" x-text="namaBulan[0]">Bulan 1</th>'
    )
    content = content.replace(
        '<th class="px-4 py-2 text-center font-bold text-gray-600 dark:text-gray-300" x-text="namaBulan[1]">Bulan 2</th>',
        '<th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700" x-text="namaBulan[1]">Bulan 2</th>'
    )
    content = content.replace(
        '<th class="px-4 py-2 text-center font-bold text-gray-600 dark:text-gray-300" x-text="namaBulan[2]">Bulan 3</th>',
        '<th class="px-4 py-3 text-center font-bold text-gray-700 dark:text-gray-200 border-b-2 border-gray-200 dark:border-gray-700" x-text="namaBulan[2]">Bulan 3</th>'
    )

    # 4. Change 'Operasional' to 'Opsnal' in the dropdown
    content = content.replace(
        '''<option value="Operasional" {{ $currentUraian == 'Operasional' ? 'selected' : '' }}>Operasional</option>''',
        '''<option value="Opsnal" {{ $currentUraian == 'Opsnal' || $currentUraian == 'Operasional' ? 'selected' : '' }}>Opsnal</option>'''
    )

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

process_file('resources/views/admin/rendis/edit.blade.php')
process_file('resources/views/admin/rendis/create.blade.php')
print("Styling improved and Opsnal added")
