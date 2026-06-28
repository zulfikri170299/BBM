import os
import re

# 1. Update backend validation rules in Controllers
controllers_dir = 'app/Http/Controllers'

# Replacements for backend validation
backend_replacements = [
    (re.compile(r"'numeric\|min:0\.0?1'"), r"'integer|min:1'"),
    (re.compile(r"'required\|numeric\|min:0\.0?1'"), r"'required|integer|min:1'"),
    (re.compile(r"'numeric\|min:0\.1'"), r"'integer|min:1'"),
    (re.compile(r"numeric\|min:0\.1"), r"integer|min:1"),
    (re.compile(r"numeric\|min:0\.01"), r"integer|min:1"),
    (re.compile(r"numeric"), r"integer"),
]

count_controllers = 0
for root, _, files in os.walk(controllers_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original = content
            for regex, rep in backend_replacements:
                content = regex.sub(rep, content)
            
            if content != original:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                count_controllers += 1
                print(f"Updated Controller: {filepath}")


# 2. Update frontend inputs in Views
views_dir = 'resources/views'
frontend_replacements = [
    (re.compile(r'step="0\.0?1"'), r'step="1"'),
    (re.compile(r'step="any"'), r'step="1"'),
    (re.compile(r"step='0\.0?1'"), r"step='1'"),
    (re.compile(r"step='any'"), r"step='1'")
]

count_views = 0
for root, _, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original = content
            for regex, rep in frontend_replacements:
                content = regex.sub(rep, content)
            
            if content != original:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                count_views += 1
                print(f"Updated View: {filepath}")

print(f"Updated {count_controllers} controllers and {count_views} views.")
