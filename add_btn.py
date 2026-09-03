import re

def process_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find the end of the overflow-x-auto div for Hari Kerja
    # We can match the end of the table and the closing div.
    regex = r'(<h3[^>]+>Jumlah Hari Kerja per Bulan</h3>[\s\S]+?</table>\s+</div>)'
    
    def repl(m):
        btn_html = '''
            <div class="mt-4 flex justify-end">
                <button type="button" @click="recalculateAll()" class="px-5 py-2 bg-blue-600 dark:bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 shadow-sm transition-all text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    Terapkan Hari Kerja ke Tabel
                </button>
            </div>'''
        return m.group(1) + btn_html

    content = re.sub(regex, repl, content)

    # Disable deep watch which was causing slow loops in large arrays
    content = content.replace("this.$watch('hari', () => {\n                this.recalculateAll();\n            }, { deep: true });", "// Watch removed in favor of manual apply button")

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

process_file('resources/views/admin/rendis/edit.blade.php')
process_file('resources/views/admin/rendis/create.blade.php')
print("Added button and removed deep watch")
