"""
Comprehensive color synchronization fix for all blade templates.
Fixes light/dark mode color pairs across the entire application.
"""
import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"
files_changed = 0
total_replacements = 0

def fix_file(filepath):
    global files_changed, total_replacements
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    original = content
    replacements = 0
    
    # ============================================================
    # 1. Fix standalone bg-gray-700 (should be dark:bg-gray-700)
    # ============================================================
    # Pattern: bg-gray-700 NOT preceded by dark:
    content = re.sub(
        r'(?<![:\w-])bg-gray-700(?!\S)',
        'bg-gray-100 dark:bg-gray-700',
        content
    )
    
    # ============================================================
    # 2. Fix standalone text-gray-300 (should be dark:text-gray-300)
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])text-gray-300(?!\S)',
        'text-gray-600 dark:text-gray-300',
        content
    )
    
    # ============================================================
    # 3. Fix TomSelect styles in app.css - handled separately
    # ============================================================
    
    # ============================================================
    # 4. Fix input/select elements that have dark:bg-gray-700 
    #    but missing bg-white for light mode
    #    Pattern: border-gray-300 dark:border-gray-600 dark:bg-gray-700
    # ============================================================
    content = content.replace(
        'border-gray-300 dark:border-gray-600 dark:bg-gray-700',
        'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white'
    )
    
    # ============================================================
    # 5. Fix bg-slate-50 dark:bg-slate-800/50 patterns
    #    These are fine for light mode (slate-50 is light)
    #    But let's make sure text colors are paired
    # ============================================================
    
    # ============================================================
    # 6. Fix text-slate-800 dark:text-white patterns that got 
    #    corrupted by previous scripts (e.g. double text-slate-800)
    # ============================================================
    # Clean up any duplicated classes from previous script runs
    content = re.sub(r'(bg-white text-slate-900 ){2,}', 'bg-white text-slate-900 ', content)
    content = re.sub(r'(bg-white dark:bg-slate-900 ){2,}', 'bg-white dark:bg-slate-900 ', content)
    
    # ============================================================
    # 7. Fix "bg-slate-100 bg-white text-slate-900" duplicates  
    #    from previous bulk scripts (bg-slate-100 was replaced
    #    but bg-white was also added, causing conflicts)
    # ============================================================
    content = content.replace(
        'bg-slate-100 bg-white text-slate-900',
        'bg-white'
    )
    
    # ============================================================
    # 8. Fix hover states: "hover:bg-slate-100 bg-white text-slate-900"
    # ============================================================
    content = content.replace(
        'hover:bg-slate-100 bg-white text-slate-900',
        'hover:bg-slate-100'
    )
    
    # ============================================================
    # 9. Fix "dark:bg-slate-800 bg-white text-slate-900" → just keep both
    # ============================================================
    # This is actually fine if structured as bg-white dark:bg-slate-800
    
    # ============================================================
    # 10. Fix "bg-white dark:bg-slate-900 border border-slate-200 
    #     dark:border-white/5" duplicated borders
    # ============================================================
    content = re.sub(
        r'border border-slate-200 dark:border-white/5 border border-slate-200 dark:border-white/5',
        'border border-slate-200 dark:border-white/5',
        content
    )
    content = re.sub(
        r'border border-slate-200 dark:border-white/10 border border-slate-200 dark:border-white/10',
        'border border-slate-200 dark:border-white/10',
        content
    )
    
    # ============================================================
    # 11. Fix broken dark: prefixes from previous runs
    #     e.g. "dark:bg-red-50 dark:bg-red-900/50" (was hover:bg-red-50)
    # ============================================================
    content = content.replace(
        'dark:bg-red-50 dark:bg-red-900',
        'hover:bg-red-50 dark:bg-red-900'
    )
    
    # ============================================================
    # 12. Fix "text-slate-800 dark:text-white" on colored backgrounds
    #     On solid colored backgrounds (bg-indigo-600, bg-rose-600 etc)
    #     the text should just be white always
    # ============================================================
    # This is tricky - skip for now, handle case by case
    
    # ============================================================
    # 13. Fix standalone bg-gray-200 that should have dark variant
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])bg-gray-200(?!\S)(?!.*dark:bg)',
        'bg-gray-200 dark:bg-gray-700',
        content
    )
    
    # ============================================================
    # 14. Fix standalone bg-gray-100 that should have dark variant
    # ============================================================
    # Don't double-add if dark: variant already follows
    
    # ============================================================  
    # 15. Fix bg-gray-300 buttons (disabled state) 
    # ============================================================
    content = content.replace(
        'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400',
        'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400'
    )
    
    # ============================================================
    # 16. Fix "bg-gray-100 text-gray-600" badges without dark mode
    # ============================================================
    content = content.replace(
        'bg-gray-100 text-gray-600 rounded',
        'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded',
    )
    
    # ============================================================
    # 17. Fix hover:bg-gray-50 without dark variant
    # ============================================================
    content = re.sub(
        r'hover:bg-gray-50(?!\s+dark:hover)',
        'hover:bg-gray-50 dark:hover:bg-gray-700/50',
        content
    )
    
    # ============================================================
    # 18. Fix bg-gray-50 without dark variant (for table headers etc)
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])bg-gray-50(?!\s+dark:bg)(?!/)',
        'bg-gray-50 dark:bg-gray-800/50',
        content
    )
    
    # ============================================================
    # 19. Fix text-gray-900 without dark variant
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])text-gray-900(?!\s+dark:text)(?!\S)',
        'text-gray-900 dark:text-white',
        content
    )
    
    # ============================================================
    # 20. Fix text-gray-500 without dark variant
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])text-gray-500(?!\s+dark:text)(?!\S)',
        'text-gray-500 dark:text-gray-400',
        content
    )
    
    # ============================================================
    # 21. Fix text-gray-700 without dark variant
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])text-gray-700(?!\s+dark:text)(?!\S)',
        'text-gray-700 dark:text-gray-300',
        content
    )
    
    # ============================================================
    # 22. Fix border-gray-200 without dark variant
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])border-gray-200(?!\s+dark:border)(?!\S)',
        'border-gray-200 dark:border-gray-700',
        content
    )
    
    # ============================================================
    # 23. Fix border-gray-300 without dark variant
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])border-gray-300(?!\s+dark:border)(?!\S)',
        'border-gray-300 dark:border-gray-600',
        content
    )
    
    # ============================================================
    # 24. Fix bg-white without dark variant (containers, cards)
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])bg-white(?!\s+dark:bg)(?!/)',
        'bg-white dark:bg-gray-800',
        content
    )
    
    # ============================================================
    # 25. Fix divide-gray-200 without dark variant
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])divide-gray-200(?!\s+dark:divide)(?!\S)',
        'divide-gray-200 dark:divide-gray-700',
        content
    )
    
    # ============================================================
    # 26. Fix bg-gray-900/50 for dark table headers  
    # ============================================================
    content = re.sub(
        r'(?<![:\w-])bg-gray-900/50',
        'bg-gray-50 dark:bg-gray-900/50',
        content
    )
    
    # ============================================================
    # CLEANUP: Fix any double dark:dark: from nested replacements
    # ============================================================
    content = content.replace('dark:dark:', 'dark:')
    
    # Fix any triple-stacked bg-white
    content = re.sub(r'(bg-white dark:bg-gray-800\s*){2,}', 'bg-white dark:bg-gray-800 ', content)
    
    # Fix double dark:bg patterns  
    content = re.sub(r'dark:bg-gray-800\s+dark:bg-gray-800', 'dark:bg-gray-800', content)
    
    # Fix double dark:text patterns
    content = re.sub(r'dark:text-white\s+dark:text-white', 'dark:text-white', content)
    
    # Fix cases where bg-gray-50 dark:bg-gray-800/50 got double-applied
    content = re.sub(
        r'bg-gray-50 dark:bg-gray-800/50 dark:bg-gray-800/50',
        'bg-gray-50 dark:bg-gray-800/50',
        content
    )
    content = re.sub(
        r'bg-gray-50 dark:bg-gray-900/50 dark:bg-gray-800/50',
        'bg-gray-50 dark:bg-gray-900/50',
        content
    )
    
    # Fix hover double-apply
    content = re.sub(
        r'dark:hover:bg-gray-700/50 dark:hover:bg-gray-700/50',
        'dark:hover:bg-gray-700/50',
        content
    )
    
    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        files_changed += 1
        return True
    return False


# Process all blade files
for root, dirs, files in os.walk(views_dir):
    # Skip print-specific templates (they have their own styling)
    rel = os.path.relpath(root, views_dir)
    if 'print' in rel.lower() and 'index' not in rel.lower():
        continue
        
    for file in files:
        if file.endswith('.blade.php'):
            # Skip print templates
            if 'print' in file.lower() and file != 'index.blade.php':
                continue
            filepath = os.path.join(root, file)
            fix_file(filepath)

print(f"✅ Updated {files_changed} files with synchronized dark/light mode colors.")
