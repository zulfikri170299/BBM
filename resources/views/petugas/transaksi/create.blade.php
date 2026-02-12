<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-bold mb-2">Detail Kendaraan</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="text-gray-600">No Polisi:</div>
                            <div class="font-bold">{{ $kendaraan->no_polisi }}</div>
                            <div class="text-gray-600">Satker:</div>
                            <div class="font-bold">{{ $kendaraan->satker->nama_satker }}</div>
                            <div class="text-gray-600">Jenis BBM:</div>
                            <div class="font-bold">{{ $kendaraan->jenis_bbm }}</div>
                            <div class="text-gray-600">Sisa Saldo:</div>
                            <div class="font-bold text-green-600">{{ number_format($kendaraan->saldo, 1) }} Liter</div>
                        </div>
                    </div>

                    <form action="{{ route('petugas.transaksi.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kendaraan_id" value="{{ $kendaraan->id }}">
                        
                        <div class="mb-4">
                            <label for="liter" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Liter:</label>
                            <input type="number" name="liter" id="liter" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        
                        <div class="mb-6">
                            <label for="pin" class="block text-gray-700 text-sm font-bold mb-2">PIN:</label>
                            <input type="password" name="pin" id="pin" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required maxlength="6">
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('petugas.transaksi.index') }}" class="text-gray-600 hover:text-gray-800">Batal</a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">Proses Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
