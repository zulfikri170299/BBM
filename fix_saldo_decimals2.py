import os
import re

directory = 'resources/views'

patterns = [
    (re.compile(r"number_format\(\$([a-zA-Z0-9_\->]+saldo), 0, ',', '\.'\)"), r"rtrim(rtrim(number_format(\$\1, 2, ',', '.'), '0'), ',')"),
    (re.compile(r"number_format\(\$([a-zA-Z0-9_\->]+saldo), 0\)"), r"rtrim(rtrim(number_format(\$\1, 2, ',', '.'), '0'), ',')"),
    (re.compile(r"number_format\(\$([a-zA-Z0-9_]+), 0, ',', '\.'\)"), r"rtrim(rtrim(number_format(\$\1, 2, ',', '.'), '0'), ',')") # for $saldo alone
]

count = 0
for root, _, files in os.walk(directory):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            original_content = content
            for regex, replacement in patterns:
                content = regex.sub(replacement, content)
            
            if content != original_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Updated {filepath}")
                count += 1

print(f"Total files updated: {count}")
