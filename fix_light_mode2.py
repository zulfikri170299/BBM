import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"

replacements = {
    # Backgrounds
    r'(?<!dark:)bg-indigo-900/30': 'bg-indigo-50 dark:bg-indigo-900/30',
    r'(?<!dark:)bg-indigo-900/20': 'bg-indigo-50/50 dark:bg-indigo-900/20',
    r'(?<!dark:)bg-violet-900/50': 'bg-violet-50 dark:bg-violet-900/50',
    r'(?<!dark:)bg-red-900/50': 'bg-red-50 dark:bg-red-900/50',
    r'(?<!dark:)bg-rose-900/20': 'bg-rose-50/50 dark:bg-rose-900/20',
    r'(?<!dark:)bg-rose-900/30': 'bg-rose-50 dark:bg-rose-900/30',
    r'(?<!dark:)bg-slate-800/30': 'bg-slate-100 dark:bg-slate-800/30',
    r'(?<!dark:)bg-slate-950/30': 'bg-slate-200/30 dark:bg-slate-950/30',
    r'(?<!dark:)bg-slate-950/50': 'bg-slate-200/50 dark:bg-slate-950/50',
    r'(?<!dark:)bg-slate-950/80': 'bg-slate-200/80 dark:bg-slate-950/80',
    
    # Borders
    r'(?<!dark:)border-white/30': 'border-slate-300 dark:border-white/30',
    r'(?<!dark:)border-white/40': 'border-slate-400 dark:border-white/40',
    r'(?<!dark:)hover:border-white/30': 'hover:border-slate-400 dark:hover:border-white/30',
    r'(?<!dark:)hover:border-white/40': 'hover:border-slate-500 dark:hover:border-white/40',
    
    # Gradients
    r'(?<!dark:)from-emerald-900/40': 'from-emerald-50 dark:from-emerald-900/40',
    r'(?<!dark:)from-indigo-900/40': 'from-indigo-50 dark:from-indigo-900/40',
    r'(?<!dark:)from-purple-900/40': 'from-purple-50 dark:from-purple-900/40',
    r'(?<!dark:)from-red-900(?!\/)': 'from-red-100 dark:from-red-900',
    r'(?<!dark:)from-red-900/60': 'from-red-50 dark:from-red-900/60',
    r'(?<!dark:)from-slate-900': 'from-slate-100 dark:from-slate-900',
    
    r'(?<!dark:)to-slate-900(?!\/)': 'to-slate-200 dark:to-slate-900',
    r'(?<!dark:)to-slate-900/60': 'to-slate-200/60 dark:to-slate-900/60',
    r'(?<!dark:)to-slate-900/80': 'to-slate-200/80 dark:to-slate-900/80',
    r'(?<!dark:)to-slate-950(?!\/)': 'to-slate-300 dark:to-slate-950',
    
    r'(?<!dark:)hover:bg-violet-900/30': 'hover:bg-violet-100 dark:hover:bg-violet-900/30',
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
            
            # Additional cleanups for text colors in the gradient boxes, which were hardcoded as text-emerald-400
            # For emerald
            content = re.sub(r'(?<!dark:)text-emerald-400', r'text-emerald-600 dark:text-emerald-400', content)
            # For indigo
            content = re.sub(r'(?<!dark:)text-indigo-400', r'text-indigo-600 dark:text-indigo-400', content)
            # For red/rose
            content = re.sub(r'(?<!dark:)text-rose-400', r'text-rose-600 dark:text-rose-400', content)
            content = re.sub(r'(?<!dark:)text-red-400', r'text-red-600 dark:text-red-400', content)
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Updated {filepath}")
