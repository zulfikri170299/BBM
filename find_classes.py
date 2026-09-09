import os
import re

views_dir = r"d:\PROJEK\BBM\BBM\resources\views"
pattern = re.compile(r'(?<!dark:)(?:bg-slate-[789]\d*(?:/\d+)?|bg-slate-950|text-white(?:/\d+)?|border-white(?:/\d+)?|from-[a-z]+-9\d*(?:/\d+)?|to-slate-9\d*(?:/\d+)?|bg-[a-z]+-900(?:/\d+)?)')

found_classes = set()

for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith(".blade.php"):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Find all class attributes
            class_matches = re.findall(r'class="([^"]+)"', content)
            for cls_str in class_matches:
                classes = cls_str.split()
                for cls in classes:
                    if pattern.search(cls) and not cls.startswith('dark:'):
                        found_classes.add(cls)

print("Classes needing dark mode sync:")
for cls in sorted(list(found_classes)):
    print(cls)
