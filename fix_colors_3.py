import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"

replacements = {
    # Fix pastel icon colors in light mode
    r'(?<!dark:)text-violet-400': 'text-violet-600 dark:text-violet-400',
    r'(?<!dark:)text-blue-400': 'text-blue-600 dark:text-blue-400',
    r'(?<!dark:)text-amber-400': 'text-amber-600 dark:text-amber-400',
    r'(?<!dark:)text-indigo-300': 'text-indigo-600 dark:text-indigo-300',
    r'(?<!dark:)text-emerald-400': 'text-emerald-600 dark:text-emerald-400',
    r'(?<!dark:)text-rose-400': 'text-rose-600 dark:text-rose-400',
    
    # Fix action buttons with grey color
    r'(?<!dark:)text-slate-500 dark:text-slate-400(?! hover| rounded| focus| border| bg| block| shadow)': 'text-slate-600 dark:text-slate-400',
    r'text-slate-500 dark:text-slate-400 hover:text-indigo-600': 'text-slate-700 dark:text-slate-400 hover:text-indigo-600',
    r'text-slate-500 dark:text-slate-400 hover:text-violet-600': 'text-slate-700 dark:text-slate-400 hover:text-violet-600',
    r'text-slate-500 dark:text-slate-400 hover:text-amber-600': 'text-slate-700 dark:text-slate-400 hover:text-amber-600',
    r'text-slate-500 dark:text-slate-400 hover:text-rose-600': 'text-slate-700 dark:text-slate-400 hover:text-rose-600',
    r'text-slate-500 dark:text-slate-400 hover:text-slate-800': 'text-slate-700 dark:text-slate-400 hover:text-slate-900',
    
    # Fix checkboxes
    r'type="checkbox"([^>]+)class="([^"]+)"': lambda m: f'type="checkbox"{m.group(1)}class="{m.group(2)} bg-white dark:bg-slate-900"' if 'bg-white' not in m.group(2) else m.group(0),
    r'class="([^"]+)type="checkbox"': lambda m: f'class="{m.group(1)} bg-white dark:bg-slate-900" type="checkbox"' if 'bg-white' not in m.group(1) else m.group(0),
}

for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith(".blade.php"):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            for pattern, replacement in replacements.items():
                if callable(replacement):
                    content = re.sub(pattern, replacement, content)
                else:
                    content = re.sub(pattern, replacement, content)
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Updated {filepath}")
