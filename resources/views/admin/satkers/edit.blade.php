<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            {{ __('Edit Satker') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12 px-2 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-white/5 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <form action="{{ route('admin.satkers.update', $satker) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="nama_satker" class="block text-slate-300 text-sm font-bold mb-2">Nama Satker:</label>
                            <input type="text" name="nama_satker" id="nama_satker" value="{{ $satker->nama_satker }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-300 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="alamat" class="block text-slate-300 text-sm font-bold mb-2">Alamat:</label>
                            <textarea name="alamat" id="alamat" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-300 leading-tight focus:outline-none focus:shadow-outline">{{ $satker->alamat }}</textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Satker</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
