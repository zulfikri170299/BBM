import re
import subprocess
import os

with open('resources/views/admin/rendis/edit.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()
    
js = re.search(r'<script>(.*?)</script>', content, re.DOTALL).group(1)

# The JS contains Blade tags like {{ $rendisBbm->triwulan }} which will cause Node to fail parsing.
# We need to replace them with dummy values for syntax checking.
js = re.sub(r'\{\{.*?\}\}', '"DUMMY"', js)

with open('test.js', 'w', encoding='utf-8') as f:
    f.write(js)

print("test.js created")
