import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"

for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith(".blade.php"):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            
            # Find and replace text-{{ $colorClass }}-400 that don't have dark: prefix
            content = re.sub(r'(?<!dark:)text-\{\{ \$colorClass \}\}-400', r'text-{{ $colorClass }}-600 dark:text-{{ $colorClass }}-400', content)
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Updated {filepath}")
