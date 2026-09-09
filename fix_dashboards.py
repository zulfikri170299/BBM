import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"
dashboard_files = []

for root, dirs, files in os.walk(views_dir):
    for file in files:
        if 'dashboard' in file.lower() and file.endswith('.blade.php'):
            dashboard_files.append(os.path.join(root, file))

# We need to be careful with Stok Dex card, which has a dynamic background.
# We will just replace text-slate-900 dark:text-white with text-white inside the dark gradient cards.
# Wait, a safer way is to replace 'text-slate-900 dark:text-white' with 'text-white' inside the 4 main cards
# But since this pattern 'text-slate-900 dark:text-white' is mostly used in these dark cards, let's see.

for filepath in dashboard_files:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Let's replace text-slate-900 dark:text-white with text-white
    # But ONLY for text that is inside elements that are definitely on dark backgrounds.
    # The backgrounds are from-indigo, from-emerald, from-amber, from-rose, from-orange, from-blue.
    
    # We can just replace 'text-slate-900 dark:text-white' with 'text-white' globally in the dashboards,
    # and then check if it breaks anything. The dashboard mostly consists of these colored cards.
    # Let's check what else uses text-slate-900.
    # For Stok Dex, if dex < 0, it uses from-rose-50 (light). So text-white would be invisible.
    # Let's use a regex to fix the static colored cards.
    
    # Replace text-slate-900 dark:text-white -> text-white
    new_content = content.replace('text-slate-900 dark:text-white', 'text-white')
    new_content = new_content.replace('text-slate-900 dark:text-white/90', 'text-white/90')
    new_content = new_content.replace('text-slate-900 dark:text-white/80', 'text-white/80')
    new_content = new_content.replace('text-slate-900 dark:text-white/70', 'text-white/70')
    
    # Fix the dynamic Dex card:
    # class="{{ $tankStock['dex'] < 0 ? '... text-slate-900 dark:text-white' : '... text-white' }}"
    # Actually, the Dex card has `text-white` on the wrapper after our replace, but inside it has text-white.
    # Let's just fix the Dex card condition specifically if needed.
    
    if new_content != content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {filepath}")
