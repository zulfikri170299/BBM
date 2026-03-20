<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                            <input type="text" name="username" id="username" value="{{ $user->username }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Peran:</label>
                            <select name="role" id="role"
                                class="tom-select shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super
                                    Admin</option>
                                <option value="kasubbag" {{ $user->role == 'kasubbag' ? 'selected' : '' }}>Kasubbag
                                </option>
                                <option value="admin_satker" {{ $user->role == 'admin_satker' ? 'selected' : '' }}>Admin
                                    Satker</option>
                                <option value="petugas_bbm" {{ $user->role == 'petugas_bbm' ? 'selected' : '' }}>Petugas
                                    BBM</option>
                                <option value="personel" {{ $user->role == 'personel' ? 'selected' : '' }}>Personel
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="satker_id" class="block text-gray-700 text-sm font-bold mb-2">Satker:</label>
                            <select name="satker_id" id="satker_id"
                                class="tom-select shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Tidak Ada</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ $user->satker_id == $satker->id ? 'selected' : '' }}>
                                        {{ $satker->nama_satker }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi
                                (Kosongkan jika tidak ingin diubah):</label>
                            <input type="password" name="password" id="password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation"
                                class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Kata Sandi:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Perbarui
                                Pengguna</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>