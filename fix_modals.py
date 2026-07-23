import os
import re

filepath = r"d:\PROJEK\BBM\BBM\resources\views\satker\kendaraans\index.blade.php"

with open(filepath, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Fix Transfer Modal
transfer_original = """        <!-- Transfer Modal -->
        <div>
    <template x-teleport="body">

                <!-- Backdrop -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 transition-opacity"
                    @click="showTransferModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-slate-900 border border-white/5 rounded-2xl shadow-2xl w-full max-w-md mx-auto max-h-[90vh] flex flex-col overflow-hidden">"""

transfer_fixed = """        <!-- Transfer Modal -->
        <div>
            <template x-teleport="body">
                <div x-show="showTransferModal" style="display: none;">
                    <!-- Backdrop -->
                    <div x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-slate-900/60 z-[9998] transition-opacity"
                        @click="showTransferModal = false"></div>

                    <!-- Modal Wrapper -->
                    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 pointer-events-none">
                        <!-- Modal Panel -->
                        <div x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                            class="relative bg-slate-900 border border-white/5 rounded-2xl shadow-2xl w-full max-w-md mx-auto max-h-[90vh] flex flex-col overflow-hidden pointer-events-auto">"""

content = content.replace(transfer_original, transfer_fixed)

# Wait, the closing tags for Transfer Modal might be slightly different now.
# Since we added <div x-show="showTransferModal" style="display: none;"> and <div class="fixed inset-0 ...">, we need to add two more closing </div>s before </template>.
# Let's find the </template> for Transfer Modal.
# It's right before <!-- Monthly Report Modal -->
transfer_close_original = """                            </div>
                        </div>
                    </form>
                </div>
    </template>
        </div>"""

transfer_close_fixed = """                            </div>
                        </div>
                    </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>"""

content = content.replace(transfer_close_original, transfer_close_fixed)


# 2. Fix Monthly Report Modal
monthly_original = """        <!-- Monthly Report Modal -->
        <div>
    <template x-teleport="body">

                <!-- Backdrop -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 transition-opacity"
                    @click="showMonthlyReportModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-slate-900 border border-white/5 rounded-2xl shadow-2xl w-full max-w-sm mx-auto max-h-[90vh] flex flex-col overflow-hidden">"""

monthly_fixed = """        <!-- Monthly Report Modal -->
        <div>
            <template x-teleport="body">
                <div x-show="showMonthlyReportModal" style="display: none;">
                    <!-- Backdrop -->
                    <div x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-slate-900/60 z-[9998] transition-opacity"
                        @click="showMonthlyReportModal = false"></div>

                    <!-- Modal Wrapper -->
                    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 pointer-events-none">
                        <!-- Modal Panel -->
                        <div x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                            class="relative bg-slate-900 border border-white/5 rounded-2xl shadow-2xl w-full max-w-sm mx-auto max-h-[90vh] flex flex-col overflow-hidden pointer-events-auto">"""

content = content.replace(monthly_original, monthly_fixed)

monthly_close_original = """                            </div>
                        </form>
                    </div>
                </div>
    </template>
        </div>"""

monthly_close_fixed = """                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>"""

content = content.replace(monthly_close_original, monthly_close_fixed)

with open(filepath, "w", encoding="utf-8") as f:
    f.write(content)

print("Modals fixed!")
