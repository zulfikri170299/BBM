import re

def move_summary(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 1. Extract the tfoot content and remove it from the table
    tfoot_pattern = r'<tfoot class="bg-gray-100.*?</tfoot>'
    tfoot_match = re.search(tfoot_pattern, content, re.DOTALL)
    
    if tfoot_match:
        content = content.replace(tfoot_match.group(0), '')
        
        # 2. Build the new summary UI outside the table
        summary_html = """
          <!-- RINGKASAN DISTRIBUSI -->
          <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
              <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ringkasan Distribusi Triwulan</h3>
              </div>
              <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Pertamax -->
                  <div class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-lg border border-blue-100 dark:border-blue-800">
                      <h4 class="text-blue-800 dark:text-blue-300 font-bold text-lg mb-4 flex items-center">
                          <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Pertamax
                      </h4>
                      <div class="space-y-3">
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B1:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b1-ptx">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B2:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b2-ptx">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm border-b border-blue-200 dark:border-blue-800 pb-3">
                              <span class="text-gray-600 dark:text-gray-400">Total B3:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b3-ptx">0</span>
                          </div>
                          <div class="flex justify-between items-center pt-1">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Total Distribusi:</span>
                              <span class="font-extrabold text-blue-700 dark:text-blue-400 text-lg"><span id="grand-total-triwulan-ptx">0</span> L</span>
                          </div>
                          <div class="flex justify-between items-center">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Maksimal (Batas):</span>
                              <span class="font-bold text-gray-900 dark:text-white"><span id="maksimal-distribusi-ptx">0</span> L</span>
                          </div>
                          <div class="mt-2 pt-3 border-t border-blue-200 dark:border-blue-800 text-right" id="status-ptx">
                              -
                          </div>
                      </div>
                  </div>
                  
                  <!-- Pertamina Dex -->
                  <div class="bg-emerald-50 dark:bg-emerald-900/20 p-5 rounded-lg border border-emerald-100 dark:border-emerald-800">
                      <h4 class="text-emerald-800 dark:text-emerald-300 font-bold text-lg mb-4 flex items-center">
                          <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span> Pertamina Dex
                      </h4>
                      <div class="space-y-3">
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B1:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b1-dex">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm">
                              <span class="text-gray-600 dark:text-gray-400">Total B2:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b2-dex">0</span>
                          </div>
                          <div class="flex justify-between items-center text-sm border-b border-emerald-200 dark:border-emerald-800 pb-3">
                              <span class="text-gray-600 dark:text-gray-400">Total B3:</span>
                              <span class="font-bold text-gray-900 dark:text-white" id="grand-total-b3-dex">0</span>
                          </div>
                          <div class="flex justify-between items-center pt-1">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Total Distribusi:</span>
                              <span class="font-extrabold text-emerald-700 dark:text-emerald-400 text-lg"><span id="grand-total-triwulan-dex">0</span> L</span>
                          </div>
                          <div class="flex justify-between items-center">
                              <span class="font-bold text-gray-700 dark:text-gray-300">Maksimal (Batas):</span>
                              <span class="font-bold text-gray-900 dark:text-white"><span id="maksimal-distribusi-dex">0</span> L</span>
                          </div>
                          <div class="mt-2 pt-3 border-t border-emerald-200 dark:border-emerald-800 text-right" id="status-dex">
                              -
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        """
        
        # 3. Find where to insert it. We'll insert it right before the "Update Rendis" button wrapper.
        # In edit.blade.php it's:
        # <div class="flex justify-end">
        #    <button type="submit" class="px-8 py-3 bg-brand-primary...
        
        insert_pattern = r'<div class="flex justify-end">\s*<button type="submit"'
        content = re.sub(
            insert_pattern,
            summary_html + '\n          <div class="flex justify-end">\n              <button type="submit"',
            content
        )
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

move_summary('resources/views/admin/rendis/edit.blade.php')
move_summary('resources/views/admin/rendis/create.blade.php')
print("Summary moved")
