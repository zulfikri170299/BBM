import re

def fix_selector(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # The JS got corrupted by the global replace
    content = content.replace(
        'document.querySelector(\'input[name="pembelian_pertamax" @input="recalculateGrandTotal()"]\')',
        'document.querySelector(\'input[name="pembelian_pertamax"]\')'
    )
    content = content.replace(
        'document.querySelector(\'input[name="pembelian_pertamina_dex" @input="recalculateGrandTotal()"]\')',
        'document.querySelector(\'input[name="pembelian_pertamina_dex"]\')'
    )
    content = content.replace(
        'document.querySelector(\'input[name="susut_persen" @input="recalculateGrandTotal()"]\')',
        'document.querySelector(\'input[name="susut_persen"]\')'
    )

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_selector('resources/views/admin/rendis/edit.blade.php')
fix_selector('resources/views/admin/rendis/create.blade.php')
print("Selectors fixed")
