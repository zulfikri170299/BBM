import os
import re

files = [
    r"d:\PROJEK\BBM\BBM\resources\views\admin\rendis\create.blade.php",
    r"d:\PROJEK\BBM\BBM\resources\views\admin\rendis\edit.blade.php"
]

for filepath in files:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find all inputs/selects that have dark:bg-gray-700 but might be missing bg-white text-gray-900
    # Let's just do a regex replace:
    # We want to replace 'dark:bg-gray-700' with 'bg-white text-gray-900 dark:bg-gray-700' if 'bg-white' is not present in that class string.
    
    # Let's replace 'border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white'
    # with 'border-gray-300 bg-white text-gray-900 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white'
    
    new_content = content.replace(
        'border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white',
        'border-gray-300 bg-white text-gray-900 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white'
    )
    
    new_content = new_content.replace(
        'border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white',
        'border-gray-300 bg-white text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white'
    )
    
    # Also check the span 'input-hari-x' in edit.blade.php:
    # border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400
    new_content = new_content.replace(
        'border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400',
        'border border-gray-300 bg-white text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400'
    )
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)
    
    print(f"Updated {filepath}")
