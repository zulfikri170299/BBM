<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Laporan Transaksi BBM') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12 px-2 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-800 dark:text-white">
                    <form action="{{ route('admin.reports.generate') }}" method="POST" target="_blank">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-slate-700 dark:text-slate-300 text-sm font-bold mb-2">Tanggal Mulai:</label>
                                <input type="date" name="start_date" id="start_date" class="bg-white dark:bg-slate-900 dark:text-white flatpickr border-2 border-slate-200 dark:border-white/10 rounded-xl w-full py-2 px-3 text-slate-700 dark:text-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium" required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-slate-700 dark:text-slate-300 text-sm font-bold mb-2">Tanggal Selesai:</label>
                                <input type="date" name="end_date" id="end_date" class="bg-white dark:bg-slate-900 dark:text-white flatpickr border-2 border-slate-200 dark:border-white/10 rounded-xl w-full py-2 px-3 text-slate-700 dark:text-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium" required>
                            </div>
                            <div>
                                <label for="type" class="block text-slate-700 dark:text-slate-300 text-sm font-bold mb-2">Format:</label>
                                <select name="type" id="type" class="tom-select w-full" required>
                                    <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="pdf">PDF</option>
                                    <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="excel">Excel</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Download Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
