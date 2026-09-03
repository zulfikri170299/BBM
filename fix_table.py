import re

def fix_table(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Add the events to the table tag
    content = content.replace(
        '<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">',
        '<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs" @input="handleTableInput" @change="handleTableInput">'
    )
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_table('resources/views/admin/rendis/edit.blade.php')
fix_table('resources/views/admin/rendis/create.blade.php')
print("Fixed table events")
