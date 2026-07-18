import os

def check_file(path):
    with open(path, 'r', encoding='utf-8') as f:
        lines = f.readlines()
        
    for i, line in enumerate(lines):
        if '@turbo:before-cache' in line:
            # Check the next 3 lines for x-teleport
            has_teleport = False
            for j in range(1, 4):
                if i + j < len(lines):
                    if 'x-teleport=' in lines[i+j] or '<template' in lines[i+j]:
                        has_teleport = True
            if not has_teleport:
                print(f"Missing teleport near line {i+1} in {path}")

# check admin and satker
check_file('d:/PROJEK/BBM/resources/views/admin/kendaraans/index.blade.php')
check_file('d:/PROJEK/BBM/resources/views/satker/kendaraans/index.blade.php')
