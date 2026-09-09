import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"
files_changed = 0

for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            
            # Replace dark:hover:text-slate-900 with dark:hover:text-white
            # Actually, sometimes it's followed by dark:text-white. 
            # e.g., hover:text-slate-900 dark:hover:text-slate-900 dark:text-white
            # If we replace dark:hover:text-slate-900 with dark:hover:text-white, it's correct.
            content = content.replace('dark:hover:text-slate-900', 'dark:hover:text-white')
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                files_changed += 1

print(f"Updated {files_changed} files.")
