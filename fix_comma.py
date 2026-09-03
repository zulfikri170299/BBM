import re

def fix_comma(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    content = content.replace('},,', '},')
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_comma('resources/views/admin/rendis/edit.blade.php')
fix_comma('resources/views/admin/rendis/create.blade.php')
print("Fixed double comma")
