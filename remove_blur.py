import os
import re

VIEWS_DIR = r"d:\PROJEK\BBM\BBM\resources\views"

# Hapus backdrop-filter: blur(...) dari inline style CSS juga
INLINE_BACKDROP = re.compile(r'\s*backdrop-filter:\s*blur\([^)]*\);?\s*\n?')

changed_files = []
total_replacements = 0

target_files = [
    r"personel\dashboard.blade.php",
    r"satker\kendaraans\print.blade.php",
    r"satker\personels\card.blade.php",
]

for rel_path in target_files:
    filepath = os.path.join(VIEWS_DIR, rel_path)
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content, count = INLINE_BACKDROP.subn('\n', content)
    
    if count > 0:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        changed_files.append((rel_path, count))
        total_replacements += count
        print(f"[{count}x] Cleaned: {rel_path}")

print(f"\nDone! Total replacements: {total_replacements}")
