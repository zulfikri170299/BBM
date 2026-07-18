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
            
            # Replace container paddings like p-2, p-3, p-4 with p-1 for mobile
            new_content = re.sub(r'class="p-[1-5]\s+sm:p-[6-8]\s+lg:p-8', 'class="p-1 sm:p-6 lg:p-8', content)
            
            if new_content != content:
                updated_count += 1
                with open(path, 'w', encoding='utf-8') as file:
                    file.write(new_content)

print(f"Updated {updated_count} files.")
