import os, re

views = r'd:\PROJEK\BBM\BBM\resources\views'
c_files = 0

def add_bg(match):
    cls = match.group(1)
    if 'bg-' not in cls:
        return 'class="' + 'bg-white dark:bg-slate-900 dark:text-white ' + cls + '"'
    return match.group(0)

for r, d, fs in os.walk(views):
    for f in fs:
        if f.endswith('.blade.php'):
            fp = os.path.join(r, f)
            with open(fp, 'r', encoding='utf-8') as fh:
                c = fh.read()
            orig = c
            
            # Match inputs with typical border class but missing background
            c = re.sub(r'class=[\"\']([^\"\']*(?:focus:ring-indigo-[0-9]+|border-slate-[0-9]+ dark:border-white\/[0-9]+)[^\"\']*)[\"\']', add_bg, c)
            
            if c != orig:
                with open(fp, 'w', encoding='utf-8') as fh:
                    fh.write(c)
                c_files += 1

print(f'Fixed {c_files} files.')
