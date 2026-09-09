import os, re

views = r'd:\PROJEK\BBM\BBM\resources\views'
count = 0

def fix_buttons(match):
    cls = match.group(1)
    
    # If it has a solid background color typically used for primary buttons
    if re.search(r'bg-(?:brand-primary|indigo-[567]00|rose-[567]00|emerald-[567]00|blue-[567]00|green-[567]00|red-[567]00)', cls):
        # And it has the messed up text classes
        if 'text-slate-900 dark:text-white' in cls:
            cls = cls.replace('text-slate-900 dark:text-white', 'text-white')
        elif 'text-slate-800 dark:text-white' in cls:
            cls = cls.replace('text-slate-800 dark:text-white', 'text-white')
            
    return f'class="{cls}"'

for r, d, fs in os.walk(views):
    for f in fs:
        if f.endswith('.blade.php'):
            fp = os.path.join(r, f)
            with open(fp, 'r', encoding='utf-8') as fh:
                c = fh.read()
            orig = c
            
            c = re.sub(r'class="([^"]+)"', fix_buttons, c)
            
            if c != orig:
                with open(fp, 'w', encoding='utf-8') as fh:
                    fh.write(c)
                count += 1

print(f"Fixed button text colors in {count} files.")
