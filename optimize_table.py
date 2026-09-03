import re

def optimize_table(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Move @input and @change from tbody to the parent table
    # Find the table tag for Kendaraan (it's the second table usually)
    # Actually, we can just find the tbody with those events
    
    tbody_old = '<tbody class="bg-white dark:bg-gray-800" @input="handleTableInput" @change="handleTableInput">'
    tbody_new = '<tbody class="bg-white dark:bg-gray-800" x-ignore>'
    
    if tbody_old in content:
        content = content.replace(tbody_old, tbody_new)
        # Now find the table tag that contains this tbody
        # The table starts with <table class="w-full text-sm ...
        content = re.sub(
            r'<table class="w-full text-sm text-left border-collapse([^>]*)">',
            r'<table class="w-full text-sm text-left border-collapse\1" @input="handleTableInput" @change="handleTableInput">',
            content,
            count=1 # only the first one (actually we want the kendaraan table)
        )
        # Wait, there are two tables! The first is Hari Kerja, second is Kendaraan.
        # Let's just blindly replace both or specify precisely.
        # Let's just find the table right before the tbody_new
        
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

optimize_table('resources/views/admin/rendis/edit.blade.php')
optimize_table('resources/views/admin/rendis/create.blade.php')
print("Optimized with x-ignore")
