import os
import re

directory = 'resources/views'

count = 0
for root, _, files in os.walk(directory):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            content = content.replace(r'number_format(\$', r'number_format($')
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Fixed {filepath}")
                count += 1

print(f"Total files fixed: {count}")
