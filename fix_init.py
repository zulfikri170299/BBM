import re

def fix_init(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    content = content.replace(
        '''setTimeout(() => {
                this.recalculateAllSatkers();
            }, 100);''',
        '''setTimeout(() => {
                this.recalculateAll();
            }, 100);'''
    )
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_init('resources/views/admin/rendis/edit.blade.php')
fix_init('resources/views/admin/rendis/create.blade.php')
print("Fixed init logic")
