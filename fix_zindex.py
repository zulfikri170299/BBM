import os
import re

views_dir = 'd:/PROJEK/BBM/resources/views'
updated_count = 0

for root, _, files in os.walk(views_dir):
    for f in files:
        if f.endswith('.blade.php'):
            path = os.path.join(root, f)
            # Skip the component modal itself (already fixed)
            if 'components' in path and 'modal.blade.php' in f:
                continue
            # Skip layout app (notifications already at z-[9999])  
            if 'layouts' in path and 'app.blade.php' in f:
                continue
                
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()

            original = content
            
            # Upgrade all z-50 to z-[9999] for fixed inset-0 modal elements
            # Pattern: class="...fixed inset-0...z-50..." -> replace z-50 with z-[9999]
            # We need to be careful to only change lines that have both "fixed inset-0" and "z-50"
            lines = content.split('\n')
            new_lines = []
            for line in lines:
                if 'fixed inset-0' in line and 'z-50' in line:
                    line = line.replace('z-50', 'z-[9999]')
                # Also upgrade z-[100] to z-[9999]
                if 'fixed inset-0' in line and 'z-[100]' in line:
                    line = line.replace('z-[100]', 'z-[9999]')
                if 'fixed inset-0' in line and 'z-[110]' in line:
                    line = line.replace('z-[110]', 'z-[9999]')
                new_lines.append(line)
            
            content = '\n'.join(new_lines)
            
            if content != original:
                updated_count += 1
                with open(path, 'w', encoding='utf-8') as file:
                    file.write(content)
                print(f"Fixed z-index in: {path}")

print(f"Total files updated: {updated_count}")
