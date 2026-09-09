import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"

replacements = {
    # Backgrounds
    r'(?<!dark:)bg-slate-900': 'bg-white dark:bg-slate-900',
    r'(?<!dark:)bg-slate-800/50': 'bg-slate-50 dark:bg-slate-800/50',
    r'(?<!dark:)bg-slate-800(?!\/)': 'bg-slate-50 dark:bg-slate-800',
    r'(?<!dark:)bg-slate-700/50': 'bg-slate-100 dark:bg-slate-700/50',
    r'(?<!dark:)bg-slate-700(?!\/)': 'bg-slate-100 dark:bg-slate-700',
    
    # Text
    r'(?<!dark:)text-white': 'text-slate-900 dark:text-white',
    r'(?<!dark:)text-slate-200': 'text-slate-800 dark:text-slate-200',
    r'(?<!dark:)text-slate-300': 'text-slate-700 dark:text-slate-300',
    r'(?<!dark:)text-slate-400': 'text-slate-500 dark:text-slate-400',
    
    # Borders
    r'(?<!dark:)border-white/5': 'border-slate-200 dark:border-white/5',
    r'(?<!dark:)border-white/10': 'border-slate-300 dark:border-white/10',
    r'(?<!dark:)border-white/20': 'border-slate-400 dark:border-white/20',
    
    # Divide
    r'(?<!dark:)divide-white/5': 'divide-slate-200 dark:divide-white/5',
    r'(?<!dark:)divide-white/10': 'divide-slate-300 dark:divide-white/10',
    
    # Hover
    r'(?<!dark:)hover:bg-slate-800/50': 'hover:bg-slate-100 dark:hover:bg-slate-800/50',
    r'(?<!dark:)hover:bg-slate-800(?!\/)': 'hover:bg-slate-100 dark:hover:bg-slate-800',
    r'(?<!dark:)hover:bg-white/5': 'hover:bg-slate-200 dark:hover:bg-white/5',
}

for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith(".blade.php"):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            for pattern, replacement in replacements.items():
                content = re.sub(pattern, replacement, content)
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Updated {filepath}")
