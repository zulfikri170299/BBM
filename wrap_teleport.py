import os
import re

def wrap_with_teleport(content):
    # Find all occurrences of <!-- Backdrop -->
    # We will look for <!-- Backdrop --> followed by some spaces and <div x-show=...
    import collections
    
    lines = content.split('\n')
    new_lines = []
    
    i = 0
    while i < len(lines):
        line = lines[i]
        
        # If we see a template x-teleport already, maybe we should skip this section
        # But let's look for <!-- Backdrop -->
        if '<!-- Backdrop -->' in line:
            # Check if this backdrop is already wrapped?
            # Look backwards 5 lines
            already_wrapped = False
            for j in range(max(0, i-5), i):
                if 'x-teleport="body"' in lines[j]:
                    already_wrapped = True
                    break
            
            if not already_wrapped:
                # We need to wrap!
                # Insert <template x-teleport="body"> BEFORE the backdrop
                new_lines.append('    <template x-teleport="body">')
                new_lines.append(line)
                
                # Now we need to parse forward, find the <!-- Modal --> and its div.
                # The modal usually is the very next main <div>.
                # We will just count opening and closing <divs> until we reach balance 0 for the Backdrop AND the Modal.
                # Actually, there are TWO sibling divs:
                # 1. Backdrop div
                # 2. Modal div
                
                i += 1
                
                # We expect 2 top-level divs to be processed.
                divs_processed = 0
                
                while i < len(lines) and divs_processed < 2:
                    current_line = lines[i]
                    new_lines.append(current_line)
                    
                    if '<div' in current_line:
                        # Simple nested counting
                        # Find the first <div, and start tracking.
                        
                        div_count = 0
                        # Count all divs in this line first
                        div_count += current_line.count('<div')
                        div_count -= current_line.count('</div')
                        
                        # Process inner lines until balance is 0
                        while div_count > 0:
                            i += 1
                            if i >= len(lines): break
                            sub_line = lines[i]
                            new_lines.append(sub_line)
                            div_count += sub_line.count('<div')
                            div_count -= sub_line.count('</div')
                        
                        divs_processed += 1
                    i += 1
                
                # Now we've processed both the Backdrop div and the Modal HTML.
                # Close the template!
                new_lines.append('    </template>')
                continue
                
        new_lines.append(line)
        i += 1
        
    return '\n'.join(new_lines)


views_dir = 'd:/PROJEK/BBM/resources/views'
updated_files = 0

for root, _, files in os.walk(views_dir):
    for f in files:
        if f.endswith('.blade.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as f_in:
                original = f_in.read()
            
            modified = wrap_with_teleport(original)
            
            if modified != original:
                with open(path, 'w', encoding='utf-8') as f_out:
                    f_out.write(modified)
                updated_files += 1
                print(f"Wrapped modals in: {path}")

print(f"Total files updated: {updated_files}")
