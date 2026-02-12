<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kendaraan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('satker.kendaraans.update', $kendaraan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="no_polisi" class="block text-gray-700 text-sm font-bold mb-2">No Polisi:</label>
                            <input type="text" name="no_polisi" id="no_polisi" value="{{ $kendaraan->no_polisi }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="jenis_kendaraan" class="block text-gray-700 text-sm font-bold mb-2">Jenis Kendaraan:</label>
                            <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" value="{{ $kendaraan->jenis_kendaraan }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="jenis_bbm" class="block text-gray-700 text-sm font-bold mb-2">Jenis BBM:</label>
                            <select name="jenis_bbm" id="jenis_bbm" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="Pertalite" {{ $kendaraan->jenis_bbm == 'Pertalite' ? 'selected' : '' }}>Pertalite</option>
                                <option value="Pertamax" {{ $kendaraan->jenis_bbm == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                                <option value="Solar" {{ $kendaraan->jenis_bbm == 'Solar' ? 'selected' : '' }}>Solar</option>
                                <option value="Dexlite" {{ $kendaraan->jenis_bbm == 'Dexlite' ? 'selected' : '' }}>Dexlite</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="pin" class="block text-gray-700 text-sm font-bold mb-2">PIN (Isi jika ingin mengubah):</label>
                            <input type="number" name="pin" id="pin" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="0" oninput="if(this.value.length > 6) this.value = this.value.slice(0, 6);">
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Kendaraan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
