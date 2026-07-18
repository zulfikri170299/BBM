import os
import re

views_dir = 'd:/PROJEK/BBM/resources/views'
updated_count = 0

for root, _, files in os.walk(views_dir):
    for f in files:
        if f.endswith('.blade.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()
            
            # Revert p-1 back to p-2 for slightly better breathing room while still edge-to-edge
            new_content = re.sub(r'class="p-1\s+sm:p-[6-8]\s+lg:p-8', 'class="p-2 sm:p-6 lg:p-8', content)
            
            if new_content != content:
                updated_count += 1
                with open(path, 'w', encoding='utf-8') as file:
                    file.write(new_content)

print(f"Updated {updated_count} files for padding.")
