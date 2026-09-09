import os, re

views = r'd:\PROJEK\BBM\BBM\resources\views'
count = 0

def fix_dark(match):
    cls = match.group(1)
    
    # 1. Clean up duplicated bg-white and bg-gray/slate
    cls = re.sub(r'bg-(?:gray|slate)-(?:50|100)\s+bg-white', 'bg-white', cls)
    cls = re.sub(r'bg-white\s+bg-(?:gray|slate)-(?:50|100)', 'bg-white', cls)
    
    # 2. Fix duplicated dark:bg classes
    darks = re.findall(r'dark:bg-[a-z0-9-/]+', cls)
    if len(darks) > 1:
        # Keep the LAST one, remove others
        for d in darks[:-1]:
            cls = cls.replace(d, '', 1)
        cls = re.sub(r'\s+', ' ', cls).strip()
        
    return f'class="{cls}"'

for r, d, fs in os.walk(views):
    for f in fs:
        if f.endswith('.blade.php'):
            fp = os.path.join(r, f)
            with open(fp, 'r', encoding='utf-8') as fh:
                c = fh.read()
            orig = c
            
            c = re.sub(r'class="([^"]+)"', fix_dark, c)
            
            if c != orig:
                with open(fp, 'w', encoding='utf-8') as fh:
                    fh.write(c)
                count += 1

print(f"Fixed duplicate backgrounds in {count} files.")
