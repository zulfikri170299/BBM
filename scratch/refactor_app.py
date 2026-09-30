import sys

with open(r'd:\PROJEK\BBM\BBM\resources\views\layouts\app.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

import re

# Replace the body tag and its layout wrapper
pattern = r'<body class="[^"]+" x-data="\{ sidebarOpen: false, desktopSidebarOpen: \$persist\(true\) \}" @sidebar-close\.window="sidebarOpen = false" @sidebar-open\.window="sidebarOpen = true">.*?<div class="flex h-full min-h-full">.*?@include\(\'layouts\.sidebar\'\).*?<!-- Main Content Wrapper -->.*?<div class="flex flex-1 flex-col overflow-hidden relative z-0">.*?@include\(\'layouts\.header\'\)'

replacement = """<body class="bg-base-200 font-sans antialiased text-base-content" x-data="{ sidebarOpen: false, isDarkMode: $persist(true) }" :data-theme="isDarkMode ? 'dark' : 'corporate'" @sidebar-close.window="sidebarOpen = false" @sidebar-open.window="sidebarOpen = true">
    <div class="drawer lg:drawer-open h-screen">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" x-model="sidebarOpen" />
        
        <div class="drawer-content flex flex-col h-screen overflow-hidden relative z-0">
            @include('layouts.header')"""

new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)

# Replace the closing div tags for the main content wrapper
pattern_end = r'            </main>\s*</div>\s*</div>'
replacement_end = """            </main>
        </div>
        
        <div class="drawer-side z-40">
            <label for="main-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            @include('layouts.sidebar')
        </div>
    </div>"""

new_content = re.sub(pattern_end, replacement_end, new_content, flags=re.DOTALL)

# Let's also modify HTML tag at line 2 to not hardcode 'dark' based on alpine, since we use data-theme now
new_content = new_content.replace(
    '<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}" class="h-full" x-data="{ isDarkMode: $persist(true) }" :class="{ \'dark\': isDarkMode }">',
    '<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}" class="h-full" x-data="{ isDarkMode: $persist(true) }" :data-theme="isDarkMode ? \'dark\' : \'corporate\'">'
)

with open(r'd:\PROJEK\BBM\BBM\resources\views\layouts\app.blade.php', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Replaced app.blade.php layout successfully.")
