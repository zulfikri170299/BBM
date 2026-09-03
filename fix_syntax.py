import re

def fix_file(file_path, is_edit=False):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Fix @php tag syntax error
    if is_edit:
        content = content.replace(
            "@php $currentUraian = {{ $rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional' }}; @endphp",
            "@php $currentUraian = $rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional'; @endphp"
        )
    else:
        content = content.replace(
            "@php $currentUraian = {{ $k->kategori_kendaraan ?? 'Operasional' }}; @endphp",
            "@php $currentUraian = $k->kategori_kendaraan ?? 'Operasional'; @endphp"
        )

    # Fix missing double curly braces for blade output
    content = content.replace(
        "<option value=\"Operasional\" { $currentUraian == 'Operasional' ? 'selected' : '' }>Operasional</option>",
        "<option value=\"Operasional\" {{ $currentUraian == 'Operasional' ? 'selected' : '' }}>Operasional</option>"
    )
    content = content.replace(
        "<option value=\"Staff\" { $currentUraian == 'Staff' ? 'selected' : '' }>Staff</option>",
        "<option value=\"Staff\" {{ $currentUraian == 'Staff' ? 'selected' : '' }}>Staff</option>"
    )
    content = content.replace(
        "<option value=\"Pimpinan\" { $currentUraian == 'Pimpinan' ? 'selected' : '' }>Pimpinan</option>",
        "<option value=\"Pimpinan\" {{ $currentUraian == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>"
    )

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

fix_file('resources/views/admin/rendis/edit.blade.php', is_edit=True)
fix_file('resources/views/admin/rendis/create.blade.php', is_edit=False)
print("Fixed syntax errors")
