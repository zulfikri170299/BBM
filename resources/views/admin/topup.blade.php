<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Global BBM Top-up') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <p class="mb-4">Use this form to add balance to ALL registered vehicles.</p>

                    <form action="{{ route('admin.topup.process') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount
                                (Liter):</label>
                            <input type="number" name="amount" id="amount"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                min="0.1" step="0.1" required>
                        </div>

                        <div class="mb-6">
                            <label for="topup_password" class="block text-gray-700 text-sm font-bold mb-2">Password Top
                                Up:</label>
                            <input type="password" name="topup_password" id="topup_password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Masukkan password top up" required>
                            @if(!auth()->user()->topup_password)
                                <p class="text-xs text-red-500 mt-1">
                                    Anda belum mengatur password top-up. <a href="{{ route('profile.edit') }}"
                                        class="underline">Atur di sini</a>.
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                data-confirm="Are you sure you want to add this amount to ALL vehicles?">Process
                                Top-up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>