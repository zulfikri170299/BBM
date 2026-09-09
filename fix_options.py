import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"

# Patterns to find class="..." attributes in <select> and <option> tags
# But actually, the issue might be that classes like `bg-slate-800` were left without `dark:` prefix?
# Wait! If the background is dark grey in light mode, it means it has `bg-slate-800` or `bg-slate-900` or `bg-gray-800` WITHOUT `dark:` prefix!
# OR it has `dark:bg-slate-900` but lacks `bg-white` AND the browser defaults to transparent, inheriting a dark parent? No, select dropdowns are rendered by the OS and don't inherit transparency.
# Let's search for any `bg-gray-` or `bg-slate-` in the views to see if there are any hardcoded dark backgrounds.

# Let's fix `<option>` tags specifically first.
files_changed = 0
for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            
            # Let's add explicit background and text colors to <option> tags if they don't have them
            # Find <option ...> and add class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white"
            # We'll just do a simple replace: `<option value=` -> `<option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value=`
            # But wait, some might already have classes. Let's do a regex that matches <option ...> and inserts the class if not present.
            
            def replace_option(match):
                tag = match.group(0)
                if 'class=' in tag:
                    return tag # Skip if it already has a class, to be safe
                # Insert class after <option
                return tag.replace('<option', '<option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white"')
                
            content = re.sub(r'<option\b[^>]*>', replace_option, content)
            
            # Now let's fix any `select` or `input` that might be missing `bg-white` and `text-slate-900`
            # For `select` and `input`, let's just make sure we add it. 
            # Actually, let's just do a blanket fix for any `dark:bg-slate-900` or `dark:bg-gray-700` in inputs/selects to ensure they have `bg-white text-slate-900`.
            # A safer approach is to replace `dark:bg-slate-900` with `bg-white text-slate-900 dark:bg-slate-900` if `bg-white` is missing.
            
            def ensure_light_mode_bg(match):
                cls = match.group(0)
                if 'bg-white' not in cls and 'bg-slate-50' not in cls and 'bg-gray-50' not in cls and 'bg-transparent' not in cls:
                    cls = cls.replace('dark:bg-', 'bg-white text-slate-900 dark:bg-')
                return cls
                
            # Replace in class attributes that contain dark:bg-
            content = re.sub(r'class="[^"]*dark:bg-(?:slate|gray)-(?:700|800|900)[^"]*"', ensure_light_mode_bg, content)
            
            # Also, check for hardcoded `bg-slate-800` or `bg-slate-900` that should be `dark:bg-slate-800`
            # Wait, this might break specific designs (like the dashboard cards). We shouldn't do it blindly.
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                files_changed += 1

print(f"Updated {files_changed} files.")
